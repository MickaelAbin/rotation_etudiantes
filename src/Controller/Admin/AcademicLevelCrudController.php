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
            // TODO set permission
            //->setEntityPermission('ROLE_SUPER_ADMIN')
            ->setEntityLabelInSingular('Niveau académique')
            ->setEntityLabelInPlural('Niveaux académiques')
            ->setDefaultSort(['id' => 'ASC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('label', 'Nom'),
        ];
    }

}
