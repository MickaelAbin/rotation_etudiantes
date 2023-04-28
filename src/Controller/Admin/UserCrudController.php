<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs')
            ->setDefaultSort(['moodleUserID' => 'ASC'])
            ;
    }

//    public function configureFilters(Filters $filters): Filters
//    {
//        return $filters
//            ->add('pseudo')
//            ->add('email');
//    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('first_name', 'Prénom'),
            TextField::new('last_name', 'Nom'),
            TextField::new('email', 'Email'),
            IdField::new('moodleUserID', 'Moodle User ID'),
            //ArrayField::new('roles', 'Rôles')->hideWhenCreating(),
        ];
    }

}
