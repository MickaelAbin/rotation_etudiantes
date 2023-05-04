<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs')
            //->setDefaultSort(['moodleUserID' => 'ASC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('moodleUserID', 'ID Moodle'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            ArrayField::new('roles', 'Rôles')->hideWhenCreating()
                // TODO set permissions
                //->setPermission('ROLE_SUPER_ADMIN'),
        ];
    }

}
