<?php

namespace App\Controller;

use App\Entity\Enrolment;
use App\Entity\ExchangeRequest;
use App\Form\ExchangeRequestType;
use App\Repository\EnrolmentRepository;
use App\Repository\ExchangeRequestRepository;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/exchange-request/", name = "exchange_request_")
 * @IsGranted("ROLE_STUDENT")
 *
 */
class ExchangeRequestController extends AbstractController
{

    /**
     * @Route(path  = "new", name = "new", methods = {"GET", "POST"})
     */
    public function new(Request $request, ExchangeRequestRepository $exchangeRequestRepository, EnrolmentRepository $enrolmentRepository): Response
    {
        $exchangeRequest = new ExchangeRequest();

        // récupère l'ID de l'Enrolment mis en session dans calendar.js
        $id = $request->getSession()->get('requestedEnrolmentID');

        if ($id != null) {
            $exchangeRequest->setRequestedEnrolment($enrolmentRepository->find($id));
        }

        $form = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exchangeRequestRepository->add($exchangeRequest, true);
            $this->addFlash('success', 'Votre demande d\'échange a bien été envoyée !');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exchange_request/new.html.twig', [
            'exchange_request' => $exchangeRequest,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_exchange_request_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ExchangeRequest $exchangeRequest, ExchangeRequestRepository $exchangeRequestRepository): Response
    {
        $form = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exchangeRequestRepository->add($exchangeRequest, true);

            return $this->redirectToRoute('app_exchange_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exchange_request/edit.html.twig', [
            'exchange_request' => $exchangeRequest,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_exchange_request_delete", methods={"POST"})
     */
    public function delete(Request $request, ExchangeRequest $exchangeRequest, ExchangeRequestRepository $exchangeRequestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exchangeRequest->getId(), $request->request->get('_token'))) {
            $exchangeRequestRepository->remove($exchangeRequest, true);
        }

        return $this->redirectToRoute('app_exchange_request_index', [], Response::HTTP_SEE_OTHER);
    }

    public function isEnrolmentEligibleForExchange(Enrolment $enrolment): bool
    {
        // check si l'utilisateur courant (Student) est le même que celui de l'Enrolment
        if ($this->getUser() === $enrolment->getStudent()) {
            return false;
        }

        if(new DateTimeImmutable() >= $enrolment->getDate()) {
            return false;
        }

        return true;
    }
}
