<?php

namespace App\Controller;


use App\Entity\AcademicLevel;
use App\Entity\Enrolment;
use App\Entity\UniversityCalendar;
use App\Repository\EnrolmentRepository;
use App\Repository\StudentRepository;
use App\Service\GuardScheduler;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
//    private ManagerRegistry $managerRegistry;
//    public function __construct(ManagerRegistry $managerRegistry) {
//        $this->managerRegistry = $managerRegistry;
//    }
    /**
     * @Route(path = "/", name = "home")
     */
    public function home()
    {
        return $this->render('home/index.html.twig');
    }
    
    /**
     * @Route(path = "/calendrier/{id}", name = "calendrier",methods = {"GET"})
     */
    public function calendrier(EnrolmentRepository $enrolmentRepository, AcademicLevel $academicLevel = null): Response
    {
        $events = $enrolmentRepository->listByAcademicLevelCalendrier($academicLevel->getId());
        $creneaux=[];
        foreach ($events as $event){
            $creneaux[]=[
                'id'=>$event->getId(),
                'date'=>$event->getDate()->format('Y-m-d'),
                'title'=>($event->getStudent()->getLastName())." ".($event->getStudent()->getFirstName()." ".($event->getClinicalRotationCategory()->getLabel())),
                'backgroundColor'=>$event->getClinicalRotationCategory()->getColor(),
                'description'=>$event->getClinicalRotationCategory()->getLabel(),


            ];
        }
        $data = json_encode($creneaux);
        return $this->render('home/calendrier.html.twig', compact('data','academicLevel'));
    }


    /**
     * @Route(path = "/listeParDate", name = "listeParDate")
     */
    public function listeParDate(EnrolmentRepository $enrolmentRepository)
    {
        $events = $enrolmentRepository->listByDate();
        $creneaux=[];
        foreach ($events as $event){
            $creneaux[]=[
                'id'=>$event->getId(),
                'date'=>$event->getDate()->format('Y-m-d'),
                'title'=>($event->getStudent()->getLastName())." ".($event->getStudent()->getFirstName()." ".($event->getClinicalRotationCategory()->getLabel())),
                'backgroundColor'=>$event->getClinicalRotationCategory()->getColor(),
                'description'=>$event->getClinicalRotationCategory()->getLabel(),
//                'startTime'=>$event->getClinicalRotationCategory()->getStartTime(),
//                'endTime'=>$event->getClinicalRotationCategory()->getEndTime(),

            ];
        }
        $data = json_encode($creneaux);
        return $this->render('home/listeParDateCalendrier.html.twig', compact('data'));
    }
//    /**
//     * @Route(path = "/moodle", name = "moodle")
//     */
//    public function test(): Response
//    {
//        $connection = $this->managerRegistry->getConnection('mdl_user');
//    }

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
     * @Route(path = "/test/{academicLevel}", name = "test")
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


}
