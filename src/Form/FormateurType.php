<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;


class FormateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Numero', null, [
            'label' => 'Numéro',
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^\d{8}$/',
                    'message' => 'Le numéro doit contenir exactement 8 chiffres.',
                ]),
            ],
        ])
        
            ->add('Nom_F', null, [
                'label' => 'Nom Formateur'
            ])
            ->add('Prenom_F', null, [
                'label' => 'Prénom Formateur'
            ])
            ->add('Email', null, [
                'constraints' => [
                    new Assert\Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            
            ->add('Specialite', ChoiceType::class, [
                'choices' => [
                    'Soft Skills' => 'soft skills',
                    'Programmation Web' => 'programmation web',
                    'Programmation Mobile' => 'programmation mobile',
                ],
                'placeholder' => 'Choisissez une spécialité',
                'label' => 'Spécialité',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formateur::class,
        ]);
    }
}
