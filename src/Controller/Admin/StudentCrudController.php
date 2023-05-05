<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Étudiant')
            ->setEntityLabelInPlural('Étudiants')
            ->setPaginatorPageSize(30)
            //->setDefaultSort(['moodleUserID' => 'ASC'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('academicLevel', 'Promotion'))
            ->add(BooleanFilter::new('isOnRotationSchedule', 'De planning de garde'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('moodleUserID', 'ID Moodle'),
            TextField::new('lastName', 'Nom'),
            TextField::new('firstName', 'Prénom'),
            AssociationField::new('academicLevel', 'Promotion'),
            TextField::new('email', 'Email'),
            BooleanField::new('isOnRotationSchedule', 'De planning de garde'),
            ArrayField::new('roles', 'Rôles')->hideWhenCreating()
            // TODO set permissions
            //->setPermission('ROLE_SUPER_ADMIN'),
        ];
    }
}
