<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class Promotion1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_promo', ChoiceType::class , [
                'choices' => [ 
                    'Horizantale'=> 'Horizantale',
                    'Verticale' => 'Verticale',
                    'Transversale' => 'Transversale'
                ],
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un type de promotion.']),
                ],
            ])
            ->add('raison', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La raison est obligatoire.']),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'La raison doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('poste_promo', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le poste promu est requis.']),
                ],
            ])
            ->add('date_promo', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de promotion est obligatoire.']),
                    new Assert\LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date ne peut pas être dans le futur.',
                    ]),
                ],
            ])
            ->add('nouv_sal', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nouveau salaire est requis.']),
                    new Assert\Positive([
                        'message' => 'Le salaire doit être un nombre positif.',
                    ]),
                ],
            ])
            ->add('avantage_supp', ChoiceType::class , [
                'choices' => [ 
                    'Stock Options'=> 'Stock_Options',
                    'Voiture de fonction' => 'Voiture_de_fonction',
                    'Logement de fonction' => 'Logement_de_fonction',
                    'Tele Travail' => 'Tele_Travail'
                ],
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un avantage supplémentaire.']),
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'constraints' => [
                    new Assert\NotNull(['message' => 'L\'utilisateur est obligatoire.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
