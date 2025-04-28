<?php

namespace App\Form;

use App\Entity\Bulletinpaie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
class BulletinpaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('employe', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                          ->where('u.role = :role')
                          ->setParameter('role', 'Employe');   // ⚠️ Respecte bien la casse et l'orthographe
            },
            'choice_label' => function(User $user) {
                return $user->getNom() . ' ' . $user->getPrenom() . ' (' . $user->getEmail() . ')';
            },
            'placeholder' => 'Sélectionnez un employé',
            'attr' => ['class' => 'form-control']
        ])
        
            ->add('mois', ChoiceType::class, [
                'choices' => [
                    'Janvier' => 1,
                    'Février' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Août' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Décembre' => 12,
                ],
                'placeholder' => 'Sélectionnez un mois',
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
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => true   // Désactivé car calculé automatiquement
                ],
                'required' => false
            ])
            ->add('salaireNet', NumberType::class, [
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => true   // Désactivé car calculé automatiquement
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bulletinpaie::class,
        ]);
    }
}
