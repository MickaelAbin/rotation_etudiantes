<?php

namespace App\Form;

use App\Entity\AcademicLevel;
use App\Entity\ClinicalRotationCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClinicalRotationCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class,[
                'label' => 'Libellé'
            ])

            ->add('startTime', TimeType::class,[
                'input' => 'datetime_immutable',
                'label' => 'Heure de début',

            ])

            ->add('endTime', TimeType::class,[
                'input' => 'datetime_immutable',
                'label' => 'Heure de fin',

            ])

            ->add('nbStudents', IntegerType::class,[
                'label' => 'Étudiant(s) par créneau'
            ])

            ->add('isOnWeekend', ChoiceType::class,[
                'label' => 'Garde de semaine ou de weekend',
                'placeholder' => 'Sélectionnez une option',
                'choices'=>[
                    'Week-end'=> true,
                    'Semaine' => false,
                ],
            ])

            ->add('academicLevel',EntityType::class,[
                'class'=>AcademicLevel::class,
                'choice_label'=>'label',
                'label'=>'Promotion',
            ])

            ->add('color',ColorType::class,[
                'label'=>'Couleur',
            ])

            ->add('save',SubmitType::class,[
                'label'=>'Enregistrer',
                'attr' => [
                    'class' => 'btn p-3 btn-primary rounded'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClinicalRotationCategory::class,
            // TODO retirer required false
            'required' => false,
        ]);
    }
}
