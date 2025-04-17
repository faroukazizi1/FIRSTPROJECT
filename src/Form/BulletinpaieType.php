<?php

namespace App\Form;

use App\Entity\Bulletinpaie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BulletinpaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employeId', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('mois', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('annee', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('salaireBrut', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control']
            ])
            ->add('deductions', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control'],
                'html5' => true,
                'required' => false
            ])
            ->add('salaireNet', NumberType::class, [
                'scale' => 2,
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
