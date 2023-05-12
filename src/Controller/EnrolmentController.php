<?php

namespace App\Controller;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use App\Form\EnrolmentType;
use App\Repository\EnrolmentRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/enrolment/", name = "enrolment_")
 */
class EnrolmentController extends AbstractController
{

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

            return $this->redirectToRoute('enrolment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrolment/new.html.twig', [
            'enrolment' => $enrolment,
            'form' => $form,
        ]);
    }


}
