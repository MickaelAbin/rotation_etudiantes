<?php

namespace App\Controller;


use App\Repository\EnrolmentRepository;
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

//    /**
//     * @Route(path = "/moodle", name = "moodle")
//     */
//    public function test(): Response
//    {
//        $connection = $this->managerRegistry->getConnection('mdl_user');
//    }

}
