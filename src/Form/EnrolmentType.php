<?php

namespace App\Form;

use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrolmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class,[
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label'=> 'Date ',
            ])
//            ->add('requestedExchange',EntityType::class,[
//                'class' => Enrolment::class,
//                'label' => function (Enrolment $enrolment) {
//                return $enrolment;
//                }
//            ])
////            ->add('proposedExchange',EntityType::class,[
////                'class'=>Enrolment::class,
////                'choice_label'=> function (Enrolment $enrolment) {
////                    return $enrolment->getProposedExchange()->getProposedEnrolment(). ' ' .$enrolment->getStudent()->getLastName() . ' ' . $enrolment->getClinicalRotationCategory()->getLabel(). ' ' . $enrolment->getDate()->format('j/m/Y');},
////                'label'=>'Proposition : ',
////            ])
            ->add('clinicalRotationCategory',EntityType::class,[
                'class'=>ClinicalRotationCategory::class,
                'choice_label'=>'label',
                'label'=>'Catégorie de garde : ',
            ])
            ->add('student',EntityType::class,[
                'class'=>Student::class,
                'choice_label'=> function (Student $student) {
                    return $student->getFirstName(). ' ' .$student->getLastName() ;},
                'label'=>'Etudiant : ',
            ])
            ->add('save',SubmitType::class,[
                'label'=>'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enrolment::class,
        ]);
    }
}
