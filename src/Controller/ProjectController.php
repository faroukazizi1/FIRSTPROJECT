<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectTask;
use App\Form\ProjectType;
use App\Form\ProjectTaskType;
use App\Repository\ProjectRepository;
use App\Repository\ProjectTaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route(name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['POST'])]
    public function new(Request $request, ProjectRepository $projectRepository, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($project);
                $entityManager->flush();

            }
        }       

        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'form' => $form->createView(),
            'show_modal' => true, 
        ]); 
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, ProjectTaskRepository $projectTaskRepository): Response
    {
        $tasks = $projectTaskRepository->findBy(['project' => $project]);
        
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,  
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit/modal', name: 'app_project_edit_modal', methods: ['GET', 'POST'])]
    public function editModal(Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project, [
            'action' => $this->generateUrl('app_project_edit', ['id' => $project->getId()])
        ]);

        return $this->render('project/_edit_modal.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_project_delete', methods: ['GET','POST'])]
    public function delete($id , ManagerRegistry $managerRegistry , ProjectRepository $projectRepository): Response
    {
      $entityManager =$managerRegistry->getManager();
      $project= $projectRepository->find($id) ;
      $entityManager->remove($project);
      $entityManager->flush();
      return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_project_task_edit', methods: ['GET', 'POST'])]
    public function edit1(Request $request, ProjectTask $projectTask, EntityManagerInterface $entityManager,ProjectRepository $projectRepository): Response
    {
        // Handle AJAX requests for modal form
        if ($request->isXmlHttpRequest() && $request->isMethod('GET')) {
            $form = $this->createForm(ProjectTaskType::class, $projectTask, [
                'action' => $this->generateUrl('app_project_task_edit', ['id' => $projectTask->getId()]),
            ]);
            
            return $this->render('project_task/_edit_form.html.twig', [
                'form' => $form->createView(),
                'project_task' => $projectTask,
                'projects' => $projectRepository->findAll(),
            ]);
        }

        // Handle regular form submission
        $form = $this->createForm(ProjectTaskType::class, $projectTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => true,
                    'message' => 'Task updated successfully!'
                ]);
            }
            
            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('app_project_task_index', [], Response::HTTP_SEE_OTHER);
        }

        // Handle form errors for AJAX submission
        if ($request->isXmlHttpRequest() && $form->isSubmitted() && !$form->isValid()) {
            return $this->json([
                'success' => false,
                'errors' => $this->getFormErrors($form),
            ], 400);
        }

        return $this->render('project_task/edit.html.twig', [
            'project_task' => $projectTask,
            'form' => $form->createView(),
        ]);
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $origin = $error->getOrigin();
            $field = $origin ? $origin->getName() : 'Erreur';
            $errors[] = ucfirst($field) . ' : ' . $error->getMessage();
        }
        return $errors;
    }
}
