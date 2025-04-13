<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', TextType::class , [
                'label' => 'Cin'
            ])
            ->add('nom', TextType::class , [
                'label' => 'First Name'
            ])
            ->add('prenom', TextType::class , [
                'label' => 'Last Name'
            ])
            ->add('email', EmailType::class , [
                'label' => 'Email'
            ])
            ->add('username', TextType::class , [
                'label' => 'Username'
            ])
            ->add('password' , PasswordType::class , [
                'label' => 'Password'
            ])
            ->add('role' , ChoiceType::class , [
                'data' => 'Employe',
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
            ])
            ->add('adresse', TextType::class , [
                'label' => 'Adresse'
            ])
            ->add('numero', TextType::class , [
                'label' => 'Numero'
            ])
            ->add('Submit', SubmitType::class , [  // Changed to lowercase 'submit'
                'label' => 'Register'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
