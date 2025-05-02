<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cin')
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('username')
        ->add('password', PasswordType::class , [
            'always_empty' => 'true'
        ])
        ->add('sexe', ChoiceType::class , [
        
            'choices' => [ 
                'Homme'=> 'Homme',
                'Femme' => 'Femme'
            ],
            'multiple' => false,

            ])
        ->add('adresse')
        ->add('numero')
        ->add('Submit',SubmitType::class)
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}