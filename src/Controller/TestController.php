<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(StudentRepository $studentRepository, ManagerRegistry $doctrine): Response
    {
        $slaveEntityManager = $doctrine->getManager('slave');
        return $this->render('home/index.html.twig', [
            'students'=>$studentRepository->trouverLesDixPremiers(),
        ]);
    }

}
