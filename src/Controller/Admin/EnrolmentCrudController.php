<?php

namespace App\Controller\Admin;

use App\Entity\Enrolment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EnrolmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Enrolment::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
