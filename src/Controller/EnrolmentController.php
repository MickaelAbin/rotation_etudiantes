<?php

namespace App\Controller;

use App\Entity\Enrolment;
use App\Form\EnrolmentType;
use App\Repository\EnrolmentRepository;
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
     * @Route(path = "", name = "index", methods = {"GET"})
     */
    public function index(EnrolmentRepository $enrolmentRepository): Response
    {
        return $this->render('enrolment/index.html.twig', [
            'enrolments' => $enrolmentRepository->findAll(),
        ]);
    }
    /**
     * @Route("countbystudent", name="count", methods={"GET"})
     */
    public function countbystudent(EnrolmentRepository $enrolmentRepository): Response
    {
        return $this->render('enrolment/index.html.twig', [
            'enrolments' => $enrolmentRepository->count(),
        ]);
    }
    /**
     * @Route("new", name="new", methods={"GET", "POST"})
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

    /**
     * @Route("{id}", name="show", methods={"GET"})
     */
    public function show(Enrolment $enrolment): Response
    {
        return $this->render('enrolment/show.html.twig', [
            'enrolment' => $enrolment,
        ]);
    }

    /**
     * @Route("{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Enrolment $enrolment, EnrolmentRepository $enrolmentRepository): Response
    {
        $form = $this->createForm(EnrolmentType::class, $enrolment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enrolmentRepository->add($enrolment, true);

            return $this->redirectToRoute('enrolment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrolment/edit.html.twig', [
            'enrolment' => $enrolment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Enrolment $enrolment, EnrolmentRepository $enrolmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enrolment->getId(), $request->request->get('_token'))) {
            $enrolmentRepository->remove($enrolment, true);
        }

        return $this->redirectToRoute('enrolment_index', [], Response::HTTP_SEE_OTHER);
    }
}
