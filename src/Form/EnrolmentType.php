<?php

namespace App\Form;

use App\Entity\Enrolment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrolmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('requestedExchange')
            ->add('proposedExchange')
            ->add('clinicalRotationCategory')
            ->add('student')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enrolment::class,
        ]);
    }
}
