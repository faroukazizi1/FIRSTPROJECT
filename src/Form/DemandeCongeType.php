<?php

namespace App\Form;

use App\Entity\Demandeconge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DemandecongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employeId', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('typeConge', ChoiceType::class, [
                'choices' => [
                    'MALADIE' => 'MALADIE',
                    'MATERNITE' => 'MATERNITE',
                    'SANS_SOLDE' => 'SANS_SOLDE',
                    'ANNUEL' => 'ANNUEL',
                    'AUTRE' => 'AUTRE',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandeconge::class,
        ]);
    }
}
