<?php

namespace App\Controller\Admin;

use App\Entity\ClinicalRotationCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Choice;

class ClinicalRotationCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClinicalRotationCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie de garde')
            ->setEntityLabelInPlural('Catégories de garde')
            ->setSearchFields(['academicLevel.label', 'label'])
            ->setTimeFormat('HH:mm')
            //->setDefaultSort(['moodleUserID' => 'ASC'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('academicLevel', 'Promotion'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->linkToRoute('university_calendar_new'))
            ->update(Crud::PAGE_INDEX, Action::EDIT,
                fn (Action $action) => $action->linkToRoute('university_calendar_edit',
                    function (UniversityCalendar $universityCalendar): array {
                        return [
                            'id' => $universityCalendar->getId()
                        ];
                    }))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm()->setPermission('ROLE_SUPER_ADMIN'),
            TextField::new('label', 'Nom'),
            AssociationField::new('academicLevel', 'Promotion'),
            TimeField::new('startTime', 'Heure de début'),
            TimeField::new('endTime', 'Heure de fin'),
            IntegerField::new('nbStudents', 'Étudiant(s) par créneau'),
            BooleanField::new('isOnWeekend', 'Garde du weekend'),
            ColorField::new('color', 'Code couleur'),
        ];
    }
}
