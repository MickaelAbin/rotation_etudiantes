<?php

namespace App\Controller;


use App\Entity\AcademicLevel;
use App\Entity\Enrolment;
use App\Repository\AcademicLevelRepository;
use App\Repository\EnrolmentRepository;
use App\Repository\StudentRepository;
use App\Service\GuardScheduler;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $events = $enrolmentRepository->listByAcademicLevelCalendrier($academicLevel->getId());
        $creneaux = [];
        foreach ($events as $event) {
            $creneaux[] = [
                'id' => $event->getId(),
                'date' => $event->getDate()->format('Y-m-d'),
                'title' => ($event->getStudent()->getLastName()) . " " . ($event->getStudent()->getFirstName() . " " . ($event->getClinicalRotationCategory()->getLabel())),
                'backgroundColor' => $event->getClinicalRotationCategory()->getColor(),
                'description' => $event->getClinicalRotationCategory()->getLabel(),


            ];
        }

        $data = json_encode($creneaux);
        return $this->render('home/calendar.html.twig', compact('data','academicLevel', 'academicLevelList'));
    }

    /**
     * @Route(path = "/enrolments-by-students/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "enrolments_by_students", methods = {"GET"})
     */
    public function enrolmentsByStudents(StudentRepository $studentRepository, AcademicLevel $academicLevel = null): Response
    {
        $students = $studentRepository->listByAcademicLevel($academicLevel->getId());

        return $this->render('home/enrolments_by_students.html.twig', [
            'students' => $students,
            'academicLevel' => $academicLevel,
            'academicLevelList' => $this->academicLevelList,
            'path' => 'enrolments_by_students',
        ]);
    }

    // TODO Supprimer les routes test
//    /**
//     * @Route(path = "/test/{academicLevel}", name = "test")
//     */
//    public function test(GuardScheduler $guardScheduler, int $academicLevel)
//    {
//        $availableDays = $guardScheduler->createAvailableDaysArray($academicLevel);
//        $students = $guardScheduler->shuffleUsersByAcademicLevel($academicLevel);
//        $clinicalRotationCategories = $guardScheduler->categorybyid($academicLevel);
//        return $this->render('test.html.twig', ['availableDays' => $availableDays,'students' => $students,'clinicalRotationCategories'=>$clinicalRotationCategories]);
//    }

    /**
     * @Route(path = "/test/{academicLevel}", name = "test",methods = {"GET"})
     */
    public function test(GuardScheduler $guardScheduler, EntityManagerInterface $entityManager, int $academicLevel)
    {
        $availableDays = $guardScheduler->createAvailableDaysArray($academicLevel);
        $students = $guardScheduler->shuffleUsersByAcademicLevel($academicLevel);
        $clinicalRotationCategories = $guardScheduler->categorybyid($academicLevel);
        $holidaysDates = $guardScheduler->createAvailableDaysHolidaysArray($academicLevel);
        $lastStudentIndex = 0;
        $nbStudents = count($students);

        foreach ($availableDays as $day) {
            $isWeekend = $day->format('N') >= 6; // Le samedi (6) et dimanche (7) sont considérés comme des week-ends

            if (in_array($day, $holidaysDates)) { // Si le jour est un jour férié, on le remplit avec des créneaux du week-end
                $isWeekend = true;
            }

            if ($isWeekend) { // création des enrolments pour les week-ends et les jours fériés
                foreach ($clinicalRotationCategories as $category) {
                    if ($category->isIsOnWeekend()) {
                        $categoryStudentsCount = $category->getNbStudents();
                        $studentIndex = ($lastStudentIndex % $nbStudents);

                        for ($i = 0; $i < $categoryStudentsCount; $i++) {
                            $enrolment = new Enrolment();
                            $enrolment->setDate(DateTimeImmutable::createFromMutable($day));
                            $enrolment->setClinicalRotationCategory($category);
                            $enrolment->setStudent($students[$studentIndex]);
                            $entityManager->persist($enrolment);

                            $lastStudentIndex++;
                            $studentIndex = ($lastStudentIndex % $nbStudents);
                        }
                    }
                }
            } else { // création des enrolments pour les jours de semaine
                foreach ($clinicalRotationCategories as $category) {
                    if (!$category->isIsOnWeekend()) {
                        $categoryStudentsCount = $category->getNbStudents();
                        $studentIndex = ($lastStudentIndex % $nbStudents);

                        for ($i = 0; $i < $categoryStudentsCount; $i++) {
                            $enrolment = new Enrolment();
                            $enrolment->setDate(DateTimeImmutable::createFromMutable($day));
                            $enrolment->setClinicalRotationCategory($category);
                            $enrolment->setStudent($students[$studentIndex]);
                            $entityManager->persist($enrolment);

                            $lastStudentIndex++;
                            $studentIndex = ($lastStudentIndex % $nbStudents);
                        }
                    }
                }
            }
        }

        $entityManager->flush();

        return $this->render('test.html.twig', [
            'message' => 'Enrolments created successfully',
            'availableDays' => $availableDays,
            'students' => $students,
            'clinicalRotationCategories' => $clinicalRotationCategories
        ]);
    }

    /**
     * @Route(path = "/test2/{academicLevel}", name = "test2",methods = {"GET"})
     */
    public function test2(Request $request, GuardScheduler $guardScheduler, EntityManagerInterface $entityManager, int $academicLevel)
    {
        $availableDays = $guardScheduler->createAvailableDaysArray($academicLevel);
        $students = $guardScheduler->shuffleUsersByAcademicLevel($academicLevel);
        $clinicalRotationCategories = $guardScheduler->categorybyid($academicLevel);
        $holidaysDates = $guardScheduler->createAvailableDaysHolidaysArray($academicLevel);
        $lastStudentIndex = 0;
        $nbStudents = count($students);
        // Récupération de la date de fin du UniversityCalendar pour l'AcademicLevel
        $universityCalendar = $entityManager->getRepository(UniversityCalendar::class)->findOneBy(['academicLevel' => $academicLevel]);
        $endDate = $universityCalendar ? $universityCalendar->getEndDate() : null;

        // Création du formulaire pour choisir la date de fin
        $universityCalendar = new UniversityCalendar();
        $universityCalendar->setEndDate($endDate); // Date de fin par défaut

        $form = $this->createForm(EndDateType::class, $universityCalendar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $endDate = $formData->getEndDate();
            foreach ($availableDays as $day) {
                $isWeekend = $day->format('N') >= 6; // Le samedi (6) et dimanche (7) sont considérés comme des week-ends

                if (in_array($day, $holidaysDates)) { // Si le jour est un jour férié, on le remplit avec des créneaux du week-end
                    $isWeekend = true;
                }

                if ($isWeekend) { // création des enrolments pour les week-ends et les jours fériés
                    foreach ($clinicalRotationCategories as $category) {
                        if ($category->isIsOnWeekend()) {
                            $categoryStudentsCount = $category->getNbStudents();
                            $studentIndex = ($lastStudentIndex % $nbStudents);

                            for ($i = 0; $i < $categoryStudentsCount; $i++) {
                                $enrolment = new Enrolment();
                                $enrolment->setDate(DateTimeImmutable::createFromMutable($day));
                                $enrolment->setClinicalRotationCategory($category);
                                $enrolment->setStudent($students[$studentIndex]);
                                $entityManager->persist($enrolment);

                                $lastStudentIndex++;
                                $studentIndex = ($lastStudentIndex % $nbStudents);
                            }
                        }
                    }
                } else { // création des enrolments pour les jours de semaine
                    foreach ($clinicalRotationCategories as $category) {
                        if (!$category->isIsOnWeekend()) {
                            $categoryStudentsCount = $category->getNbStudents();
                            $studentIndex = ($lastStudentIndex % $nbStudents);

                            for ($i = 0; $i < $categoryStudentsCount; $i++) {
                                $enrolment = new Enrolment();
                                $enrolment->setDate(DateTimeImmutable::createFromMutable($day));
                                $enrolment->setClinicalRotationCategory($category);
                                $enrolment->setStudent($students[$studentIndex]);
                                $entityManager->persist($enrolment);

                                $lastStudentIndex++;
                                $studentIndex = ($lastStudentIndex % $nbStudents);
                            }
                        }
                    }
                }
            }

            $entityManager->flush();

            return $this->render('test.html.twig', [
                'message' => 'Enrolments created successfully',
                'availableDays' => $availableDays,
                'students' => $students,
                'clinicalRotationCategories' => $clinicalRotationCategories,
                'form' => $form->createView()
            ]);
        }
    }
}
