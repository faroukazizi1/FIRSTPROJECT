<?php

namespace App\Form;

use App\Entity\ProjectTask;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;  
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Task Title'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
                'label' => 'Description'
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Due Date'
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'To Do' => 'todo',
                    'In Progress' => 'in_progress',
                    'Completed' => 'completed'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Status',
                'required' => true,
                'placeholder' => $isEdit ? false : 'Choose a status' // Show placeholder only for new tasks
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-control'],
                'label' => 'Project',
                'placeholder' => $isEdit ? false : 'Select a project' // Show placeholder only for new tasks
            ])
            ->add('user_id', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'User ID'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectTask::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'project_task_form',
            'is_edit' => false, // Default to false (create mode)
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}