<?php

// src/Form/BulletinpaieType.php
namespace App\Form;

use App\Entity\Bulletinpaie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BulletinpaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe_id', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('mois', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('annee', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('salaire_brut', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control']
            ])
            ->add('deductions', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control'],
                'html5' => true,
                'required' => false // If deductions can be empty
            ])
            ->add('salaire_net', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_generation', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bulletinpaie::class,
        ]);
    }
}
