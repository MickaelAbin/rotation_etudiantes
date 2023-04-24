<?php

namespace App\Form;

use App\Entity\UniversityCalendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniversityCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate',DateTimeType::class,[
                'input' => 'datetime_immutable',
                'label'=> 'date de début',
            ])
            ->add('endDate',DateTimeType::class,[
                'input' => 'datetime_immutable',
                'label'=> 'date de fin',
            ])
            ->add('Enregistrer',SubmitType::class,['label'=>'Enregistrer',
                'attr'=>[
                    'class'=>'button is-rounded'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UniversityCalendar::class,
        ]);
    }
}
