<?php

namespace App\Controller;


use App\Entity\AcademicLevel;
use App\Entity\Admin;
use App\Entity\Enrolment;
use App\Entity\Student;
use App\Entity\UniversityCalendar;
use App\Repository\AcademicLevelRepository;
use App\Repository\EnrolmentRepository;
use App\Repository\StudentRepository;
use App\Service\GuardScheduler;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $academicLevelList;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelList = $academicLevelRepository->findAll();
    }

    /**
     * @Route(path = "/", name = "home")
     */
    public function home(): Response
    {
        $user = $this->getUser();

        if ($user instanceof Admin)
        {
            return $this->render('home/home_admin.html.twig');
        }

        if ($user instanceof Student)
        {
            return $this->render('home/home_student.html.twig');
        }

        return $this->render('home/home.html.twig');
    }

    /**
     * @Route(path = "/enrolments-by-categories", name = "enrolments_by_categories")
     */
    // TODO supprimer code fantôme "liste par date" (twig enrolments_by_categories) ou implémenter la fonctionnalité
//    public function listeParDate(EnrolmentRepository $enrolmentRepository)
//    {
//        $events = $enrolmentRepository->listByDate();
//        $creneaux=[];
//        foreach ($events as $event){
//            $creneaux[]=[
//                'id'=>$event->getId(),
//                'date'=>$event->getDate()->format('Y-m-d'),
//                'title'=>($event->getStudent()->getLastName())." ".($event->getStudent()->getFirstName()." ".($event->getClinicalRotationCategory()->getLabel())),
//                'backgroundColor'=>$event->getClinicalRotationCategory()->getColor(),
//                'description'=>$event->getClinicalRotationCategory()->getLabel(),
//                'startTime'=>$event->getClinicalRotationCategory()->getStartTime(),
//                'endTime'=>$event->getClinicalRotationCategory()->getEndTime(),
//
//            ];
//        }
//        $data = json_encode($creneaux);
//        return $this->render('home/enrolments_by_categories.twig', compact('data'));
//    }

    /**
     * @Route(path = "/calendar/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "calendar", methods = {"GET"})
     */
    public function calendar(EnrolmentRepository $enrolmentRepository, AcademicLevel $academicLevel = null): Response
    {
        $academicLevelList = $this->academicLevelList;
        $enrolments = $enrolmentRepository->findEnrolmentsByAcademicLevel($academicLevel->getId());

        $fullCalendarData = [];
        foreach ($enrolments as $enrolment) {
            $fullCalendarData[] = [
                'id' => $enrolment->getId(),
                'date' => $enrolment->getDate()->format('Y-m-d'),
                'title' => $enrolment->getClinicalRotationCategory()->getStartTime()->format('H') . 'h ' . $enrolment->getStudent(),
                'backgroundColor' => $enrolment->getClinicalRotationCategory()->getColor(),
                'extendedProps' => [
                    'student' => (string) $enrolment->getStudent(),
                    'academicLevel' => $enrolment->getStudent()->getAcademicLevel()->getLabel(),
                    'clinicalRotationCategory' => $enrolment->getClinicalRotationCategory()->getLabel(),
                    'startTime' => $enrolment->getClinicalRotationCategory()->getStartTime()->format('H'),
                    'endTime' => $enrolment->getClinicalRotationCategory()->getEndTime()->format('H'),
                ]
            ];
        }

        $data = json_encode($fullCalendarData);

        return $this->render('home/calendar.html.twig', compact('data','academicLevel', 'academicLevelList', 'enrolments'));
    }

    /**
     * @Route(path = "/enrolments-by-students/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "enrolments_by_students", methods = {"GET"})
     */
    public function enrolmentsByStudents(StudentRepository $studentRepository, AcademicLevel $academicLevel = null): Response
    {
        $students = $studentRepository->getStudentsWithEnrolmentsByAcademicLevel($academicLevel->getId());

        return $this->render('home/enrolments_by_students.html.twig', [
            'students' => $students,
            'academicLevel' => $academicLevel,
            'academicLevelList' => $this->academicLevelList,
            'path' => 'enrolments_by_students',
        ]);
    }



    /**
     * @Route(path="/enrolment-allocation/{id<[2,3,4,5,6]>}",defaults = {"id": 2}, name="enrolment_allocation", methods={"GET", "POST"})
     */
    public function allocation(Request $request,FormFactoryInterface $formFactory, GuardScheduler $guardScheduler, EntityManagerInterface $entityManager, AcademicLevel $academicLevel= null,EnrolmentRepository $enrolmentRepository)
    {

        // Mélange les étudiants en fonction du nombre de garde par level academic
        $students = $guardScheduler->shuffleUsersByAcademicLevel($academicLevel->getId());

        // Récupère les catégories de garde par level academic
        $clinicalRotationCategories = $guardScheduler->categorybyid($academicLevel->getId());


        $lastStudentIndex = 0;
        $nbStudents = count($students);

        // Récupération de la date de fin du UniversityCalendar pour l'AcademicLevel
        $universityCalendar = $entityManager->getRepository(UniversityCalendar::class)->findOneBy(['academicLevel' => $academicLevel]);
        $endDate = $universityCalendar ? $universityCalendar->getEndDate() : null;

        // Affichage du message d'erreur si aucun UniversityCalendar n'a été trouvé
        if (!$universityCalendar) {
            $formBuilder = $formFactory->createBuilder()
                ->add('endDate', DateType::class, [
                    'input' => 'datetime_immutable',
                    'label' => 'Date de fin',
                    'widget' => 'single_text',
                ]);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);
            return $this->render('enrolment_allocation.html.twig', [
                'form' => $form->createView(),
                'academicLevelList' => $this->academicLevelList,
                'academicLevel' => $academicLevel,
                'endDate' => null,
                'firstDate' => null,
                'message' => 'Aucun calendrier universitaire n\'a été trouvé pour cet AcademicLevel. Veuillez créer un calendrier universitaire.',
                'universityCalendar' => null,
            ]);
        }

        // Récupère un tableau de jours avec gardes par level academic
        $holidaysDates = $guardScheduler->createAvailableDaysHolidaysArray($academicLevel->getId());

        // Récupération de la date du dernier enrolment pour l'AcademicLevel
        $firstEnrolment = $enrolmentRepository->findLastEnrolmentForAcademicLevel($academicLevel->getId());
        $firstDate = $firstEnrolment ? $firstEnrolment->getDate()->add(new DateInterval('P1D')) : $universityCalendar->getStartDate();

        $formBuilder = $formFactory->createBuilder()
            ->add('endDate', DateType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Date de fin',
                'widget' => 'single_text',
            ]);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $endDate = $data['endDate'];

        $availableDays = $guardScheduler->createAvailableDaysArray($academicLevel->getId(),$firstDate,$endDate);



            foreach ($availableDays as $day) {
                $isWeekend = $day->format('N') >= 6; // Le samedi (6) et dimanche (7) sont considérés comme des week-ends

                if (in_array($day, $holidaysDates)) { // Si le jour est un jour férié, on le remplit avec des créneaux du week-end
                    $isWeekend = true;
                }

                foreach ($clinicalRotationCategories as $category) {
                    if ($category->isIsOnWeekend() === $isWeekend) { // création des enrolments pour les jours de la semaine ou les week-ends
                        $categoryStudentsCount = $category->getNbStudents();
                        $studentIndex = $lastStudentIndex % $nbStudents;

                        for ($i = 0; $i < $categoryStudentsCount; $i++) {
                            $enrolment = new Enrolment();
                            $enrolment->setDate($day);
                            $enrolment->setClinicalRotationCategory($category);
                            $enrolment->setStudent($students[$studentIndex]);
                            $entityManager->persist($enrolment);

                            $lastStudentIndex++;
                            $studentIndex = $lastStudentIndex % $nbStudents;
                        }
                    }
                }
            }

            $entityManager->flush();
            $this->addFlash('success', "L'attribution a bien été validée");


            return $this->render('enrolment_allocation.html.twig', [
                'availableDays' => $availableDays,
                'students' => $students,
                'universityCalendar' => $universityCalendar,
                'clinicalRotationCategories' => $clinicalRotationCategories,
                'academicLevelList' => $this->academicLevelList,
                'academicLevel' => $academicLevel,
                'endDate' => $endDate,
                'firstDate'=>$firstDate,
                'firstEnrolment'=>$firstEnrolment,
                'form' => $form->createView(),

            ]);
        }


        return $this->render('enrolment_allocation.html.twig', [
            'students' => $students,
            'universityCalendar' => $universityCalendar,
            'clinicalRotationCategories' => $clinicalRotationCategories,
            'academicLevelList' => $this->academicLevelList,
            'academicLevel' => $academicLevel,
            'endDate' => $endDate,
            'firstDate'=>$firstDate,
            'firstEnrolment'=>$firstEnrolment,
            'form' => $form->createView()
        ]);
    }

}
