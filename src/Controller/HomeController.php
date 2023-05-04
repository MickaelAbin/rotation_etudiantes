<?php

namespace App\Controller;


use App\Entity\AcademicLevel;
use App\Entity\UniversityCalendar;
use App\Repository\EnrolmentRepository;
use App\Service\GuardScheduler;
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
     * @Route(path = "/calendrier", name = "calendrier")
     */
    public function calendrier(EnrolmentRepository $enrolmentRepository)
    {
        $events = $enrolmentRepository->findAll();
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
        return $this->render('home/calendrier.html.twig', compact('data'));
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
    /**
     * @Route(path = "/test", name = "test")
     */
    public function test(GuardScheduler $guardScheduler)
    {
        $universityCalendar = $this->getDoctrine()->getRepository(UniversityCalendar::class)->find(1);
        $availableDays = $guardScheduler->createAvailableDaysArray($universityCalendar);

        return $this->render('test.html.twig',['availableDays'=> $availableDays]);
    }
    /**
     * @Route("/users/{academicLevelId}/shuffle", name="user_shuffle")
     */
    public function test2(GuardScheduler $guardScheduler, int $academicLevelId): Response
    {
        $users = $guardScheduler->shuffleUsersByAcademicLevel($academicLevelId);

        return $this->render('test2.html.twig', [
            'users' => $users,
        ]);
    }
}
