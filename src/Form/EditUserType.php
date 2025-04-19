<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('username')
            ->add('PlainPassword' , PasswordType::class , [
                'always_empty' => 'true' ,
                'mapped' => false,
                'required' => false,
                'label' => 'New Password (leave blank to keep current)',
                'hash_property_path' => 'password',
            ])
            ->add('role' , ChoiceType::class , [
            
                'choices' => [ 
                    'Responsable Ressource Humaine'=> 'RHR',
                    'Employe' => 'Employe'
                ],
                'multiple' => false,
    
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
