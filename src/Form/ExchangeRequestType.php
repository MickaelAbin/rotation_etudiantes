<?php

namespace App\Form;

use App\Entity\Enrolment;
use App\Entity\ExchangeRequest;
use App\Entity\Student;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;

class ExchangeRequestType extends AbstractType
{

//    private $security;
//
//    public function __constructor(Security $security){
//        $this->security = $security;
//    }

//    private $getUser;
//
//    public function __constructor(User $user){
//        $this->getUser = $user;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('requestedEnrolment', EntityType::class,)
            ->add('proposedEnrolment',EntityType::class,[
                'class'=> Enrolment::class,
                'choice_label'=> function (Enrolment $enrolment) {
                    $students = $enrolment->getStudent();
                    $clinicalRotationCategory = $enrolment->getClinicalRotationCategory();
                    $date = $enrolment->getDate()->format('d/m/Y');
                    $startTime = $clinicalRotationCategory->getStartTime()->format('H:i');
                    $endTime = $clinicalRotationCategory->getEndTime()->format('H:i');
//                    $currentUser = $this->getUser;
                    // Condition pour sélectionner l'étudiant qui propose l'échange
                    if ($students->getMoodleUserId() === true) {
                        return $students->getFirstName(). ' '.$students->getLastName(). ' - ' .$clinicalRotationCategory->getLabel(). ' - ' .$date. ' ' .$startTime. '-' .$endTime;
                    }
                },
                'label'=>'Proposition : ',
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Proposer échange',
                'attr' => [
                    'class' => 'btn p-3 btn-primary rounded'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExchangeRequest::class,
            'data_class1' => Enrolment::class
        ]);
    }
}
