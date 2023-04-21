<?php

namespace App\Controller;

use App\Entity\ClinicalRotationCategory;
use App\Form\ClinicalRotationCategoryType;
use App\Repository\ClinicalRotationCategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clinical/rotation/category")
 */
class ClinicalRotationCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_clinical_rotation_category_index", methods={"GET"})
     */
    public function index(ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository): Response
    {
        return $this->render('clinical_rotation_category/index.html.twig', [
            'clinical_rotation_categories' => $clinicalRotationCategoriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_clinical_rotation_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository): Response
    {
        $clinicalRotationCategory = new ClinicalRotationCategory();
        $form = $this->createForm(ClinicalRotationCategoryType::class, $clinicalRotationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clinicalRotationCategoriesRepository->add($clinicalRotationCategory, true);

            return $this->redirectToRoute('app_clinical_rotation_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clinical_rotation_category/new.html.twig', [
            'clinical_rotation_category' => $clinicalRotationCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_clinical_rotation_category_show", methods={"GET"})
     */
    public function show(ClinicalRotationCategory $clinicalRotationCategory): Response
    {
        return $this->render('clinical_rotation_category/show.html.twig', [
            'clinical_rotation_category' => $clinicalRotationCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_clinical_rotation_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ClinicalRotationCategory $clinicalRotationCategory, ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository): Response
    {
        $form = $this->createForm(ClinicalRotationCategoryType::class, $clinicalRotationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clinicalRotationCategoriesRepository->add($clinicalRotationCategory, true);

            return $this->redirectToRoute('app_clinical_rotation_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clinical_rotation_category/edit.html.twig', [
            'clinical_rotation_category' => $clinicalRotationCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_clinical_rotation_category_delete", methods={"POST"})
     */
    public function delete(Request $request, ClinicalRotationCategory $clinicalRotationCategory, ClinicalRotationCategoriesRepository $clinicalRotationCategoriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinicalRotationCategory->getId(), $request->request->get('_token'))) {
            $clinicalRotationCategoriesRepository->remove($clinicalRotationCategory, true);
        }

        return $this->redirectToRoute('app_clinical_rotation_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
