<?php

namespace App\Controller;

use App\Entity\Enrolment;
use App\Entity\ExchangeRequest;
use App\Form\ExchangeRequestType;
use App\Repository\EnrolmentRepository;
use App\Repository\ExchangeRequestRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new(Request $request, ExchangeRequestRepository $exchangeRequestRepository, EnrolmentRepository $enrolmentRepository, EntityManagerInterface $entityManager): Response
    {
        $exchangeRequest = new ExchangeRequest();

        $enrolment = $entityManager->find(Enrolment::class, $this->getUser()->getUserIdentifier());
        //On initialise le formulaire
        $exchangeRequestForm = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
        //On traite le formulaire
        $exchangeRequestForm->handleRequest($request);
//        // récupère l'ID de l'Enrolment mis en session dans calendar.js
//        $id = $request->getSession()->get('requestedEnrolmentID');
//
//        if ($id != null) {
//            $exchangeRequest->setRequestedEnrolment($enrolmentRepository->find($id));
//        }
//
//        $form = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
//        $form->handleRequest($request);

        if ($exchangeRequestForm->isSubmitted() && $exchangeRequestForm->isValid()) {
            $exchangeRequestRepository->add($exchangeRequest, true);
            $this->addFlash('success', 'Votre demande d\'échange a bien été envoyée !');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exchange_request/new.html.twig', [
            'exchange_request' => $exchangeRequest,
            'exchangeRequestForm' => $exchangeRequestForm,
        ]);

    }


    /**
     * @Route("afficher", name="afficherEnrolment", methods={"GET", "POST"})
     */
    public function afficherEnrolment(Request $request, ExchangeRequestRepository $exchangeRequestRepository, EntityManagerInterface $entityManager): Response
    {
        $exchangeRequest = new ExchangeRequest();
        $enrolment = new Enrolment();

        if($this->getUser() === $enrolment->getStudent()->getMoodleUserId()){
            $enrolment->setStudent($enrolment->getStudent()->getFirstName());
            $enrolment->setStudent($enrolment->getStudent()->getLastName());
            $enrolment->setClinicalRotationCategory($enrolment->getClinicalRotationCategory()->getLabel());
            $enrolment->setClinicalRotationCategory($enrolment->getClinicalRotationCategory()->getStartTime());
            $enrolment->setClinicalRotationCategory($enrolment->getClinicalRotationCategory()->getEndTime());
            //On initialise le formulaire
            $exchangeRequestForm = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
            //On traite le formulaire
            $exchangeRequestForm->handleRequest($request);

            $enrolment = $entityManager->find(Enrolment::class, $this->getUser()->getUserIdentifier());
            //On initialise le formulaire
            $exchangeRequestForm = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
            //On traite le formulaire
            $exchangeRequestForm->handleRequest($request);

            //Si le formulaire est valide
            if ($exchangeRequestForm->isSubmitted() && $exchangeRequestForm->isValid()) {

                $exchangeRequestRepository->add($exchangeRequest, true);

                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('exchange_request/afficher.html.twig.html.twig', [
                'exchange_request' => $exchangeRequest,
                'form' => $exchangeRequestForm,
            ]);
        }


    }

//    /**
//     * @Route("/{id}", name="delete", methods={"POST"})
//     */
//    public function delete(Request $request, ExchangeRequest $exchangeRequest, ExchangeRequestRepository $exchangeRequestRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$exchangeRequest->getId(), $request->request->get('_token'))) {
//            $exchangeRequestRepository->remove($exchangeRequest, true);
//        }
//
//        return $this->redirectToRoute('app_exchange_request_index', [], Response::HTTP_SEE_OTHER);
//    }

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
