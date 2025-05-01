<?php

// src/Form/ValidationTestType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidationTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions']; // Récupérer les questions dynamiques

        foreach ($questions as $index => $question) {
            $builder->add('question' . ($index + 1), TextType::class, [
                'label' => $question->getQuestionText(), // Assure-toi que la question a une méthode getQuestionText()
                'required' => true,
            ]);
        }

        $builder->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
            'label' => 'Soumettre'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'questions' => [], // L'option questions est vide par défaut
        ]);
    }
}
