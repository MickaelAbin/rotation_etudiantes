<?php

namespace App\Controller;

use App\Entity\UniversityCalendar;
use App\Form\UniversityCalendarType;
use App\Repository\UniversityCalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/university/calendar")
 */
class UniversityCalendarController extends AbstractController
{
    /**
     * @Route("/", name="app_university_calendar_index", methods={"GET"})
     */
    public function index(UniversityCalendarRepository $universityCalendarRepository): Response
    {
        return $this->render('university_calendar/index.html.twig', [
            'university_calendars' => $universityCalendarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newcalendar", name="app_university_calendar_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UniversityCalendarRepository $universityCalendarRepository): Response
    {
        $universityCalendar = new UniversityCalendar();
        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() === $form->get('Enregistrer')) {
                $universityCalendarRepository->add($universityCalendar, true);
            }
            return $this->redirectToRoute('app_university_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/new.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_university_calendar_show", methods={"GET"})
     */
    public function show(UniversityCalendar $universityCalendar): Response
    {
        return $this->render('university_calendar/show.html.twig', [
            'university_calendar' => $universityCalendar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_university_calendar_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UniversityCalendar $universityCalendar, UniversityCalendarRepository $universityCalendarRepository): Response
    {
        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $universityCalendarRepository->add($universityCalendar, true);

            return $this->redirectToRoute('app_university_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/edit.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_university_calendar_delete", methods={"POST"})
     */
    public function delete(Request $request, UniversityCalendar $universityCalendar, UniversityCalendarRepository $universityCalendarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$universityCalendar->getId(), $request->request->get('_token'))) {
            $universityCalendarRepository->remove($universityCalendar, true);
        }

        return $this->redirectToRoute('app_university_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}
