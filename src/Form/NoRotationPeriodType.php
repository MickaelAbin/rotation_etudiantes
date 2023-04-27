<?php

namespace App\Form;

use App\Entity\NoRotationPeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoRotationPeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label'=> false,
                'required' => false,
                'row_attr' => [
                    'class' => 'col-lg-6 col-sm-12 mb-3'
                ]
            ])

            ->add('endDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label'=> false,
                'required' => false,
                'row_attr' => [
                    'class' => 'col-lg-6 col-sm-12 mb-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoRotationPeriod::class
        ]);
    }
}