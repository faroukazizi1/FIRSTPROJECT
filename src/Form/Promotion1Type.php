<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ])
            ->add('raison')
            ->add('poste_promo')
            ->add('date_promo', null, [
                'widget' => 'single_text',
            ])
            ->add('nouv_sal')
            ->add('avantage_supp' , ChoiceType::class , [
                'choices' => [ 
                    'Stock Options'=> 'Stock_Options',
                    'Voiture de fonction' => 'Voiture_de_fonction',
                    'Logement de fonction' => 'Logement_de_fonction',
                    'Tele Travail' => 'Tele_Travail'
                ],
                'multiple' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom(); // Adjust these methods to match your entity
                },
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
