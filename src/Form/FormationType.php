<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // ✅ Correct
 // Import the built-in DateType
 use Symfony\Component\Form\Extension\Core\Type\UrlType;



class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Titre', ChoiceType::class, [
            'choices' => [
                'Softskills' => 'Softskills',
                'Programmation mobile' => 'Programmation mobile',
                'Programmation web' => 'Programmation web',
            ],
            'placeholder' => 'Choisissez un titre',
            'label' => 'Titre de la formation'
        ])
            ->add('Description')
            ->add('dateD', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de début',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d')
                ]
            ])
            
            ->add('dateF', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            
            
            ->add('Duree')
            ->add('Image', UrlType::class, [
                'label' => 'Image URL',
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://example.com/image.jpg'
                ]
            ])            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'choice_label' => function (Formateur $formateur) {
                    return $formateur->getNomF() . ' ' . $formateur->getPrenomF();
                },
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
