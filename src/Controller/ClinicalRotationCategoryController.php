<?php

namespace App\Controller;

use App\Controller\Admin\ClinicalRotationCategoryCrudController;
use App\Entity\ClinicalRotationCategory;
use App\Form\ClinicalRotationCategoryType;
use App\Repository\ClinicalRotationCategoriesRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path = "/admin/clinical-rotation-category/", name = "clinical_rotation_category_" )
 */
class ClinicalRotationCategoryController extends AbstractController
{
    /**
     * @Route(path = "new", name = "new", methods = {"GET", "POST"})
     */
    public function new(Request $request, ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository,
                        AdminUrlGenerator $adminUrlGenerator, EventDispatcherInterface $dispatcher): Response
    {
        $clinicalRotationCategory = new ClinicalRotationCategory();
        $form = $this->createForm(ClinicalRotationCategoryType::class, $clinicalRotationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clinicalRotationCategoriesRepository->add($clinicalRotationCategory, true);

            $dispatcher->dispatch(new AfterEntityPersistedEvent($clinicalRotationCategory));

            $url = $adminUrlGenerator->setController(ClinicalRotationCategoryCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url, Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clinical_rotation_category/new.html.twig', [
            'clinical_rotation_category' => $clinicalRotationCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route(path = "{id}/edit", name = "edit", methods = {"GET", "POST"})
     */
    public function edit(Request $request, ClinicalRotationCategory $clinicalRotationCategory, ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository,
                         AdminUrlGenerator $adminUrlGenerator, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(ClinicalRotationCategoryType::class, $clinicalRotationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clinicalRotationCategoriesRepository->add($clinicalRotationCategory, true);

            $dispatcher->dispatch(new AfterEntityUpdatedEvent($clinicalRotationCategory));

            $url = $adminUrlGenerator->setController(ClinicalRotationCategoryCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url, Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clinical_rotation_category/edit.html.twig', [
            'clinical_rotation_category' => $clinicalRotationCategory,
            'form' => $form,
        ]);
    }
}
