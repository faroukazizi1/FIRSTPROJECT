<?php

namespace App\Controller\GestionProjet;

use Knp\Component\Pager\PaginatorInterface; //bundle pagination 
use Knp\Snappy\Pdf; //bundle 
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
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route(name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        $project = new Project(); // Create new empty instance of Project entity
        $form = $this->createForm(ProjectType::class, $project); // Create form linked to Project entity
        
        // Calculate dashboard statistics
        $totalProjects = $projectRepository->count([]);
        $completedProjects = $projectRepository->count(['statut' => 'completed']);
        $uncompletedProjects = $projectRepository->count(['statut' => ['not_started', 'in_progress', 'on_hold']]);

        return $this->render('GestionProjet/project/index.html.twig', [
            'projects' => $projectRepository->findAll(), // Get all projects
            'form' => $form->createView(),
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'uncompletedProjects' => $uncompletedProjects,
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
                $this->addFlash('success', 'Projet enregistré avec succès.');
                return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement du projet. Veuillez vérifier les champs.');
            }
        }
    
        // Calculate dashboard statistics
        $totalProjects = $projectRepository->count([]);
        $completedProjects = $projectRepository->count(['statut' => 'completed']);
        $uncompletedProjects = $projectRepository->count(['statut' => ['not_started', 'in_progress', 'on_hold']]);

        return $this->render('GestionProjet/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'form' => $form->createView(),
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'uncompletedProjects' => $uncompletedProjects,
        ]);
    }
    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, ProjectTaskRepository $projectTaskRepository): Response
    {
        $tasks = $projectTaskRepository->findBy(['project' => $project]); // Récupère toutes les tâches
        
        return $this->render('GestionProjet/project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,  
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);// Lie les données de la requête au formulaire : 
                                        // si POST, remplit $project avec les nouvelles valeurs.
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush(); // Enregistre en base toutes les modifications faites sur $project.
                $this->addFlash('success', 'Projet modifié avec succès.');
                
                // Si la requête vient d’un appel AJAX (modal), on renvoie du JSON.
                if ($request->isXmlHttpRequest()) { //Vérifie si la requête HTTP a été faite en AJAX
                    return new JsonResponse([ //Instancie une réponse de type JSON
                        'success' => true,
                        'message' => 'Project updated successfully'
                    ]);
                }
                
                return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // For AJAX requests with errors, render the form with errors
                if ($request->isXmlHttpRequest()) {
                    return $this->render('GestionProjet/project/_edit_modal.html.twig', [ // (en cas d’erreur ou d’affichage).
                        'project' => $project,
                        'form' => $form->createView(),
                    ]);
                }
            }
        }
    
        // Check if this is an AJAX request for the modal
        if ($request->isXmlHttpRequest()) {
            return $this->render('GestionProjet/project/_edit_modal.html.twig', [
                'project' => $project,
                'form' => $form->createView(),
            ]);
        }
    
        return $this->render('GestionProjet/project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_project_delete', methods: ['GET','POST'])]
    public function delete($id, ManagerRegistry $managerRegistry, ProjectRepository $projectRepository): Response
    {
        $entityManager = $managerRegistry->getManager();
        $project = $projectRepository->find($id);
        $entityManager->remove($project); // Marque l’objet pour suppression : Doctrine l’enregistrera à la prochaine flush().
        $entityManager->flush(); // Exécute toutes les opérations en attente (ici : DELETE SQL du projet).
        $this->addFlash('success', 'Projet supprimé avec succès.');
        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/project/pdf', name: 'app_project_pdf', methods: ['GET'])]
    public function generatePdf(ProjectRepository $projectRepository, Pdf $knpSnappy): Response
    {
        $projects = $projectRepository->findAll();

        foreach ($projects as $project) {
            if (!$project->getId()) {
                $this->addFlash('error', 'Une project a un ID invalide.');
                return $this->redirectToRoute('app_project_index');
            }
        }

        $html = $this->renderView('GestionProjet/project/pdf.html.twig', [
            'projects' => $projects,
        ]);

        $pdfContent = $knpSnappy->getOutputFromHtml($html);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="projects.pdf"',
        ]);
    } 


    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(ProjectRepository $projectRepository): Response
    {
        // Calculate statistics
        $totalProjects = $projectRepository->count([]);
        $completedProjects = $projectRepository->count(['statut' => 'completed']);
        $uncompletedProjects = $projectRepository->count(['statut' => ['not_started', 'in_progress', 'on_hold']]);

        // Render dashboard template with variables
        return $this->render('GestionProjet/project/index.html.twig', [
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'uncompletedProjects' => $uncompletedProjects,
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