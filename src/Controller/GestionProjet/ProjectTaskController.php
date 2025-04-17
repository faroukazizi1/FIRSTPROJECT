<?php

namespace App\Controller\GestionProjet;

use App\Entity\ProjectTask;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProjectTaskRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProjectTaskType;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/task')]
final class ProjectTaskController extends AbstractController
{
    #[Route(name: 'app_project_task_index', methods: ['GET'])]
    public function index(ProjectTaskRepository $projectTaskRepository, ProjectRepository $projectRepository): Response
    {
    
        $projectTask = new ProjectTask();
        $form = $this->createForm(ProjectTaskType::class, $projectTask, [
            'is_admin' => true // Pass this to the form to enable user selection
        ]);
        
        return $this->render('GestionProjet/project_task/index.html.twig', [
            'project_tasks' => $projectTaskRepository->findAll(), // Show all tasks
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_project_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check if user has admin role
      

        $projectTask = new ProjectTask();
        $form = $this->createForm(ProjectTaskType::class, $projectTask, [
            'is_admin' => true // Enable user selection
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($projectTask);
                $entityManager->flush();
                $this->addFlash('success', 'Task created successfully.');
                return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'Error creating task. Please check the fields.');
            }
        }
    
        return $this->render('GestionProjet/project_task/index.html.twig', [
            'project_task' => $projectTask,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/tasksClient', name: 'tasksClient', methods: ['GET'])]
    public function TasksListClient(ProjectTaskRepository $taskRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('User must be logged in to view tasks.');
        }
    
        return $this->render('FrontOffice/TasksList.html.twig', [
            'to_do' => $taskRepo->findByStatutAndUserId('todo', $user->getUserIdentifier()),
            'in_progress' => $taskRepo->findByStatutAndUserId('in_progress', $user->getUserIdentifier()),
            'completed' => $taskRepo->findByStatutAndUserId('completed', $user->getUserIdentifier()),
        ]);
    }

    #[Route('/update-statut', name: 'task_update_statut', methods: ['POST'])]
    public function updateStatut(Request $request, ProjectTaskRepository $taskRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $taskId = $data['id'];
        $newStatut = $data['statut'];

        $task = $taskRepository->find($taskId);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], 404);
        }

        // Allow admins to update any task's status
        if (!$this->isGranted('ROLE_RHR') && $task->getUser() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Access denied'], 403);
        }

        $task->setStatut($newStatut);
        $em->persist($task);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}', name: 'app_project_task_show', methods: ['GET'])]
    public function show(ProjectTask $projectTask): Response
    {
       
      

        return $this->render('GestionProjet/project_task/show.html.twig', [
            'project_task' => $projectTask,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectTask $projectTask, EntityManagerInterface $entityManager, ProjectRepository $projectRepository): Response
    {
     

        $form = $this->createForm(ProjectTaskType::class, $projectTask, [
            'is_edit' => true,
            'is_admin' => true // Enable user selection for admins
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('GestionProjet/project_task/edit.html.twig', [
            'project_task' => $projectTask,
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_project_task_delete', methods: ['GET', 'POST'])]
    public function delete($id, ManagerRegistry $managerRegistry, ProjectTaskRepository $projectTaskRepository): Response
    {
     

        $projectTask = $projectTaskRepository->find($id);
        
        if (!$projectTask) {
            throw $this->createNotFoundException('Task not found');
        }

        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($projectTask);
        $entityManager->flush();
        
        $this->addFlash('success', 'Task deleted successfully.');
        return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
    }
}