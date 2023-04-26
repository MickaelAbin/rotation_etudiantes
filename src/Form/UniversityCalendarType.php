<?php

namespace App\Form;

use App\Entity\AcademicLevel;
use App\Entity\NoRotationPeriod;
use App\Entity\UniversityCalendar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniversityCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('academicLevel',EntityType::class,[
                'class'=>AcademicLevel::class,
                'choice_label'=>'label',
                'label'=>'Promotion'
            ])
            ->add('startDate',DateType::class,[
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label'=> 'Date de début',
            ])
            ->add('endDate',DateType::class,[
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label'=> 'Date de fin',
            ])
            ->add('publicHolidays', CollectionType::class,[
                'label' => 'Jours fériés avec garde',
                'entry_type' => PublicHolidayType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false,
                ]
            ])
            ->add('noRotationPeriods', CollectionType::class, [
                'label' => 'Périodes sans garde',
                'entry_type' => NoRotationPeriodType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false,
                ]
            ])

            ->add('save',SubmitType::class,[
                'label'=>'Enregistrer',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UniversityCalendar::class,
            'trim' => true,
            // TODO delete required false after testing
            'required' => false,

        ]);
    }
}
