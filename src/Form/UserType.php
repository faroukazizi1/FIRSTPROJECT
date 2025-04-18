<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le CIN est requis.']),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Le CIN doit contenir exactement {{ limit }} chiffres.',
                    ]),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Le CIN doit être un nombre.']),
                ],
            ])
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est requis.']),
                    new Assert\Length(['min' => 2]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est requis.']),
                    new Assert\Length(['min' => 2]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est requis.']),
                    new Assert\Email(['message' => 'L\'email n\'est pas valide.']),
                ],
            ])
            ->add('username', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom d\'utilisateur est requis.']),
                    new Assert\Length(['min' => 3]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est requis.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [ 
                    'Responsable Ressource Humaine' => 'RHR',
                    'Employe' => 'Employe'
                ],
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un rôle.']),
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [ 
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ],
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner le sexe.']),
                ],
            ])
            ->add('adresse', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'adresse est requise.']),
                ],
            ])
            ->add('numero', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro est requis.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro doit contenir exactement 8 chiffres.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
