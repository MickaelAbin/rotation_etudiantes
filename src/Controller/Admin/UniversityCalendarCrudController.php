<?php

namespace App\Controller\Admin;

use App\Entity\UniversityCalendar;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class UniversityCalendarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UniversityCalendar::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Calendrier universitaire')
            ->setEntityLabelInPlural('Calendriers universitaires')
            ->setDefaultSort(['academicLevel.label' => 'ASC'])
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
            IdField::new('id'),
            AssociationField::new('academicLevel', 'Promotion'),
            DateField::new('startDate', 'Date de rentrée'),
            DateField::new('endDate', 'Date de fin'),
        ];
    }
}
