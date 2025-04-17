<?php

namespace App\Form;

use App\Entity\Pret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Montant_pret', NumberType::class, [
                'label' => 'Montant du prêt',
                'attr' => ['class' => 'form-control'],
                'scale' => 2,
            ])
            ->add('Date_pret', DateType::class, [
                'label' => 'Date du prêt',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('TMM', NumberType::class, [
                'label' => 'TMM',
                'attr' => ['class' => 'form-control'],
                'scale' => 2,
            ])
            ->add('Taux', NumberType::class, [
                'label' => 'Taux',
                'attr' => ['class' => 'form-control'],
                'scale' => 2,
            ])
            ->add('Revenus_bruts', NumberType::class, [
                'label' => 'Revenus bruts',
                'attr' => ['class' => 'form-control'],
                'scale' => 2,
            ])
            ->add('Age_employe', IntegerType::class, [
                'label' => 'Âge de l\'employé',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('duree_remboursement', IntegerType::class, [
                'label' => 'Durée de remboursement (en mois)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Cadre' => 'Cadre',
                    'Employé' => 'Employé',
                    'Ouvrier' => 'Ouvrier',
                    
                ],
                'placeholder' => 'Choisissez une catégorie',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pret::class,
        ]);
    }}