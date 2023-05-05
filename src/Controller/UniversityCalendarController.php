<?php

namespace App\Controller;

use App\Controller\Admin\UniversityCalendarCrudController;
use App\Entity\NoRotationPeriod;
use App\Entity\PublicHoliday;
use App\Entity\UniversityCalendar;
use App\Form\UniversityCalendarType;
use App\Repository\UniversityCalendarRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/admin/university-calendar/", name = "university_calendar_")
 */
class UniversityCalendarController extends AbstractController
{
    /**
     * @Route(path = "new", name = "new", methods = {"GET", "POST"})
     */
    public function new(Request $request, UniversityCalendarRepository $universityCalendarRepository,
                        AdminUrlGenerator $adminUrlGenerator): Response
    {
        $universityCalendar = new UniversityCalendar();
        $universityCalendar->addPublicHoliday(new PublicHoliday());
        $universityCalendar->addNoRotationPeriod(new NoRotationPeriod());

        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() === $form->get('save')) {
                $universityCalendarRepository->add($universityCalendar, true);
                $this->addFlash('success', 'Calendrier créé avec succès !');
            }

            $url = $adminUrlGenerator->setController(UniversityCalendarCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url, Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/new.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }

    /**
     * @Route(path = "{id}/edit", name = "edit", methods = {"GET", "POST"})
     */
    public function edit(Request $request, UniversityCalendar $universityCalendar,
                         UniversityCalendarRepository $universityCalendarRepository, AdminUrlGenerator $adminUrlGenerator): Response
    {
        $form = $this->createForm(UniversityCalendarType::class, $universityCalendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $universityCalendarRepository->add($universityCalendar, true);

            $this->addFlash('success', 'Calendrier modifié avec succès !');

            $url = $adminUrlGenerator->setController(UniversityCalendarCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url, Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('university_calendar/edit.html.twig', [
            'university_calendar' => $universityCalendar,
            'form' => $form,
        ]);
    }
}
