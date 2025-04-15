<?php
namespace App\Form;

use App\Entity\Reponse;
use App\Repository\PretRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReponseType extends AbstractType
{
    private $pretRepository;

    public function __construct(PretRepository $pretRepository)
    {
        $this->pretRepository = $pretRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Si les choix ne sont pas passés en options, on les récupère depuis la base
        $cinChoices = $options['cin_choices'] ?? $this->getCinChoices();

        $builder
            // Champ Date_reponse
            ->add('Date_reponse', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Réponse',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ CIN
            ->add('cin', ChoiceType::class, [
                'choices' => $options['cin_choices'],  // Utilisation de la liste des CIN passés depuis le contrôleur
                'placeholder' => 'Sélectionner un CIN',
                // 'constraints' => [
                //     new NotBlank(['message' => 'Le CIN est obligatoire.']),
                // ],
            ])

            // Champ Montant_demande
            ->add('Montant_demande', MoneyType::class, [
                'label' => 'Montant demandé',
                'required' => true,
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Revenus_bruts
            ->add('Revenus_bruts', NumberType::class, [
                'label' => 'Revenus bruts',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Taux_interet
            ->add('Taux_interet', NumberType::class, [
                'label' => 'Taux d\'intérêt',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01',
                ]
            ])
            // Champ Mensualite_credit
            ->add('Mensualite_credit', MoneyType::class, [
                'label' => 'Mensualité crédit',
                'required' => true,
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Potentiel_credit
            ->add('Potentiel_credit', MoneyType::class, [
                'label' => 'Potentiel de crédit',
                'required' => true,
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Duree_remboursement
            ->add('Duree_remboursement', NumberType::class, [
                'label' => 'Durée de remboursement (en mois)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Montant_autorise
            ->add('Montant_autorise', MoneyType::class, [
                'label' => 'Montant autorisé',
                'required' => true,
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Champ Assurance
            ->add('Assurance', NumberType::class, [
                'label' => 'Assurance',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 1  // Ensures only integer values are accepted
                ]
            ]);
    }

    private function getCinChoices(): array
    {
        $cins = $this->pretRepository->findDistinctCins();
        return array_combine($cins, $cins); // Crée un tableau clé => valeur identique
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
            'cin_choices' => [], // Permet de surcharger les choix si besoin
        ]);
    }
}
