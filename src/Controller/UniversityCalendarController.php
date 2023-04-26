<?php

namespace App\Controller;

use App\Entity\NoRotationPeriod;
use App\Entity\PublicHoliday;
use App\Entity\UniversityCalendar;
use App\Form\UniversityCalendarType;
use App\Repository\UniversityCalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/university-calendar/", name = "university_calendar_")
 */
class UniversityCalendarController extends AbstractController
{
    /**
     * @Route(path = "", name = "index", methods = {"GET"})
     */
    public function index(UniversityCalendarRepository $universityCalendarRepository): Response
    {
        dump($universityCalendarRepository->findAll());
        return $this->render('university_calendar/index.html.twig', [
            'university_calendars' => $universityCalendarRepository->findAll(),
        ]);
    }

    /**
     * @Route(path = "new", name = "new", methods = {"GET", "POST"})
     */
    public function new(Request $request, UniversityCalendarRepository $universityCalendarRepository): Response
    {
        $universityCalendar = new UniversityCalendar();
        $universityCalendar->addPublicHoliday(new PublicHoliday());
        $universityCalendar->addNoRotationPeriod(new NoRotationPeriod());

        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() === $form->get('save')) {
                $universityCalendarRepository->add($universityCalendar, true);
            }
            return $this->redirectToRoute('university_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/new.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }

    /**
     * @Route(path = "/{id}", name = "show", methods = {"GET"})
     */
    public function show(UniversityCalendar $universityCalendar): Response
    {
        return $this->render('university_calendar/show.html.twig', [
            'university_calendar' => $universityCalendar,
        ]);
    }

    /**
     * @Route(path = "/{id}/edit", name = "edit", methods = {"GET", "POST"})
     */
    public function edit(Request $request, UniversityCalendar $universityCalendar, UniversityCalendarRepository $universityCalendarRepository): Response
    {

        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $universityCalendarRepository->add($universityCalendar, true);

            return $this->redirectToRoute('university_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/edit.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }

    /**
     * @Route(path = " /{id}", name="app_university_calendar_delete", methods={"POST"})
     */
    public function delete(Request $request, UniversityCalendar $universityCalendar, UniversityCalendarRepository $universityCalendarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$universityCalendar->getId(), $request->request->get('_token'))) {
            $universityCalendarRepository->remove($universityCalendar, true);
        }

        return $this->redirectToRoute('university_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}
