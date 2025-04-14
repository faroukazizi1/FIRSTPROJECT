<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProjectTaskRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ProjectTask;
use App\Form\ProjectTaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
final class ProjectTaskController extends AbstractController
{
    #[Route(name: 'app_project_task_index', methods: ['GET'])]
    public function index(ProjectTaskRepository $projectTaskRepository ,  ProjectRepository $projectRepository): Response
    {
        $projectTask = new ProjectTask();
        $form = $this->createForm(ProjectTaskType::class, $projectTask);
        return $this->render('project_task/index.html.twig', [
            'project_tasks' => $projectTaskRepository->findAll(), 
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projectTask = new ProjectTask();
        $form = $this->createForm(ProjectTaskType::class, $projectTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projectTask);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_task/new.html.twig', [
            'project_task' => $projectTask,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_task_show', methods: ['GET'])]
    public function show(ProjectTask $projectTask): Response
    {
        return $this->render('project_task/show.html.twig', [
            'project_task' => $projectTask,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_task_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        ProjectTask $projectTask, 
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository
    ): Response
    {
        $form = $this->createForm(ProjectTaskType::class, $projectTask, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Ensure the project relation is properly set
            if ($form->has('project') && $projectTask->getProject()) {
                $projectTask->setProjectId($projectTask->getProject()->getId());
            }
            
            $entityManager->flush();
    
            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('project_task/edit.html.twig', [
            'project_task' => $projectTask,
            'form' => $form->createView(),
            'projects' => $projectRepository->findAll(), // Pass projects for dropdown
        ]);
    }

    #[Route('/project_task/{id}/edit/modal', name: 'app_project_task_edit_modal', methods: ['GET'])]
    public function editModal(Request $request, ProjectTask $projectTask): Response
    {
        $form = $this->createForm(ProjectTaskType::class, $projectTask, [
            'action' => $this->generateUrl('app_project_task_edit', ['id' => $projectTask->getId()])
        ]);

        return $this->render('project_task/_edit_modal_form.html.twig', [
            'form' => $form->createView(),
            'projectTask' => $projectTask
        ]);
    }

    #[Route('/delete/{id}', name: 'app_project_task_delete', methods: ['GET', 'POST'])]
    public function delete($id, ManagerRegistry $managerRegistry, ProjectTaskRepository $projectTaskRepository): Response
    {
        $entityManager = $managerRegistry->getManager();
        $projectTask = $projectTaskRepository->find($id);
        $entityManager->remove($projectTask);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
