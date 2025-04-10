<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_promo')
            ->add('raison')
            ->add('poste_promo')
            ->add('date_promo', null, [
                'widget' => 'single_text',
            ])
            ->add('nouv_sal')
            ->add('avantage_supp')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
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
