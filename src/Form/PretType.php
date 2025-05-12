<?php

namespace App\Form;

use App\Entity\Pret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class PretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'attr' => [
                    'placeholder' => 'Votre numéro CIN',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le CIN est obligatoire']),
                    new Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le CIN doit contenir exactement 8 chiffres'
                    ]),
                ]
            ])
            ->add('revenusBruts', NumberType::class, [
                'label' => 'Revenus Bruts Mensuels (TND)',
                'attr' => [
                    'placeholder' => 'Votre salaire mensuel brut',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Les revenus sont obligatoires']),
                    new GreaterThan([
                        'value' => 600,
                        'message' => 'Les revenus doivent être supérieurs à 600 TND'
                    ]),
                ]
            ])
            ->add('ageEmploye', NumberType::class, [
                'label' => 'Âge',
                'attr' => [
                    'placeholder' => 'Votre âge',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'âge est obligatoire']),
                    new GreaterThan([
                        'value' => 18,
                        'message' => 'Vous devez avoir au moins 18 ans'
                    ]),
                    new LessThanOrEqual([
                        'value' => 65,
                        'message' => 'Vous devez avoir moins de 65 ans'
                    ]),
                ]
            ])
            ->add('montantPret', NumberType::class, [
                'label' => 'Montant du prêt souhaité (TND)',
                'attr' => [
                    'placeholder' => 'Montant du prêt',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le montant du prêt est obligatoire']),
                    new GreaterThan([
                        'value' => 1000,
                        'message' => 'Le montant minimum est de 1000 TND'
                    ]),
                ]
            ])
            ->add('datePret', DateType::class, [
                'label' => 'Date de demande',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control'],
                'data' => new \DateTime(), // Date actuelle par défaut
            ])
            ->add('dureeRemboursement', NumberType::class, [
                'label' => 'Durée de remboursement (années)',
                'attr' => [
                    'placeholder' => 'Durée en années',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La durée est obligatoire']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'La durée doit être positive'
                    ]),
                    new LessThanOrEqual([
                        'value' => 25,
                        'message' => 'La durée maximale est de 25 ans'
                    ]),
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie de prêt',
                'choices' => [
                    'Prêt immobilier' => 'Immobilier',
                    'Prêt automobile' => 'Automobile',
                    'Prêt personnel' => 'Personnel',
                    'Prêt étudiant' => 'Etudiant',
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La catégorie est obligatoire']),
                ]
            ])
            ->add('taux', NumberType::class, [
                'label' => 'Taux d\'intérêt (%)',
                'attr' => [
                    'placeholder' => 'Taux d\'intérêt',
                    'class' => 'form-control',
                ],
                'data' => 5.0, // Valeur par défaut
                'constraints' => [
                    new NotBlank(['message' => 'Le taux est obligatoire']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le taux doit être positif'
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pret::class,
        ]);
    }
}