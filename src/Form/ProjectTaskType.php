<?php

namespace App\Form;

use App\Entity\ProjectTask;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;

class ProjectTaskType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'] ?? false;
        $isEdit = $options['is_edit'] ?? false;
        $currentUser = $this->security->getUser();

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
                'placeholder' => $isEdit ? false : 'Choose a status'
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'titre',
                'attr' => ['class' => 'form-control'],
                'label' => 'Project',
                'placeholder' => $isEdit ? false : 'Select a project'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // or whatever field you want to display
                'attr' => ['class' => 'form-control'],
                'label' => 'Assigned To',
                'data' => $currentUser, // Set current user as default
                'disabled' => true, // Make it non-editable
                'required' => true
            ]);
            if ($isAdmin) {
                $builder->add('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'username', // or whatever field you want to display
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Assign To',
                    'required' => true,
                    'placeholder' => 'Select user'
                ]);
            }
        }
    

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ProjectTask::class,
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'csrf_token_id' => 'project_task_form',
                'is_edit' => false,
                'is_admin' => false,
            ]);
        
            $resolver->setAllowedTypes('is_edit', 'bool');
            $resolver->setAllowedTypes('is_admin', 'bool');
        }
}