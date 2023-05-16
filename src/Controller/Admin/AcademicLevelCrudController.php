<?php

namespace App\Controller\Admin;

use App\Entity\AcademicLevel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AcademicLevelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AcademicLevel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_SUPER_ADMIN')
            ->setEntityLabelInSingular('Niveau académique')
            ->setEntityLabelInPlural('Niveaux académiques')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideWhenCreating(),
            TextField::new('label', 'Libellé'),
        ];
    }

}
