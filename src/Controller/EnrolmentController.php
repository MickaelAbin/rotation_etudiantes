<?php

namespace App\Controller;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use App\Entity\UniversityCalendar;
use App\Form\EnrolmentType;
use App\Repository\AcademicLevelRepository;
use App\Repository\EnrolmentRepository;
use App\Service\GuardScheduler;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/enrolment/", name = "enrolment_")
 */
class EnrolmentController extends AbstractController
{

    private $academicLevelList;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelList = $academicLevelRepository->findAll();
    }

    /**
     * @Route(path = "countbystudent", name="count", methods={"GET"})
     */
    public function countbystudent(EnrolmentRepository $enrolmentRepository): Response
    {
        return $this->render('enrolment/index.html.twig', [
            'enrolments' => $enrolmentRepository->count(),
        ]);
    }

    /**
     * @Route(path = "new", name = "new", methods = {"GET", "POST"})
     */
    public function new(Request $request, EnrolmentRepository $enrolmentRepository): Response
    {
        $enrolment = new Enrolment();
        $form = $this->createForm(EnrolmentType::class, $enrolment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enrolmentRepository->add($enrolment, true);

            return $this->redirectToRoute('calendar', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrolment/new.html.twig', [
            'enrolment' => $enrolment,
            'form' => $form,
        ]);
    }

    /**
     * @Route(path = "allocation/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "allocation", methods = {"GET", "POST"})
     */
    public function allocation(Request $request,FormFactoryInterface $formFactory, GuardScheduler $guardScheduler, EntityManagerInterface $entityManager,
                               AcademicLevel $academicLevel, EnrolmentRepository $enrolmentRepository)
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
            return $this->render('enrolment/allocation.html.twig', [
                'form' => $form->createView(),
                'academicLevelList' => $this->academicLevelList,
                'academicLevel' => $academicLevel,
                'endDate' => null,
                'firstDate' => null,
                'message' => 'Aucun calendrier universitaire n\'a été trouvé pour cette promotion. Veuillez créer un calendrier universitaire.',
                'universityCalendar' => null,
            ]);
        }

        // Récupère un tableau de jours avec gardes par level academic
        $holidaysDates = $guardScheduler->createAvailableDaysHolidaysArray($academicLevel->getId());

        // Récupération de la date du dernier enrolment pour l'AcademicLevel
        $firstEnrolment = $enrolmentRepository->findLastEnrolmentForAcademicLevel($academicLevel->getId());
        $firstDate = $firstEnrolment ? $firstEnrolment->getDate()
            ->add(new DateInterval('P1D')) : $universityCalendar->getStartDate();

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

            $weekendDays = [];
            $weekDays = [];

            foreach ($availableDays as $day) {
                $isWeekend = $day->format('N') >= 6; // Le samedi (6) et dimanche (7) sont considérés comme des week-ends

                if (in_array($day, $holidaysDates)) { // Si le jour est un jour férié, on le remplit avec des créneaux du week-end
                    $isWeekend = true;
                    $weekendDays[] = $day; // Ajouter le jour férié au tableau des jours de week-end
                } elseif ($isWeekend) {
                    $weekendDays[] = $day; // Ajouter le jour au tableau des jours de week-end
                } else {
                    $weekDays[] = $day; // Ajouter le jour au tableau des jours de semaine
                }
            }

            // Remplir les créneaux du week-end
            foreach ($weekendDays as $day) {
                $isWeekend = true;

                foreach ($clinicalRotationCategories as $category) {
                    if ($category->isIsOnWeekend() === $isWeekend) {
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

            // Remplir les créneaux de la semaine
            foreach ($weekDays as $day) {
                $isWeekend = false;

                foreach ($clinicalRotationCategories as $category) {
                    if ($category->isIsOnWeekend() === $isWeekend) {
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
            $this->addFlash('success', "L'attribution a bien été effectuée");

            return $this->render('enrolment/allocation.html.twig', [
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

        return $this->render('enrolment/allocation.html.twig', [
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
