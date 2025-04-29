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
    
    #[Route('/{id}', name: 'app_project_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show($id, ProjectRepository $projectRepository, ProjectTaskRepository $projectTaskRepository): Response
    {
        $projet = $projectRepository->find($id);
    
        if (!$projet) {
            throw $this->createNotFoundException('Le projet demandé n\'existe pas.');
        }
    
        // Passer le projet à la vue Twig
        return $this->render('GestionProjet/project/details.html.twig', [
            'projet' => $projet
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
                
                // Si la requête vient d'un appel AJAX (modal), on renvoie du JSON.
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
                    return $this->render('GestionProjet/project/_edit_modal.html.twig', [ // (en cas d'erreur ou d'affichage).
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
        $entityManager->remove($project); // Marque l'objet pour suppression : Doctrine l'enregistrera à la prochaine flush().
        $entityManager->flush(); // Exécute toutes les opérations en attente (ici : DELETE SQL du projet).
        $this->addFlash('success', 'Projet supprimé avec succès.');
        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
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

    //PDF Bundle ----------------------------------------------------------------
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

    //Stat Metier avancer-----------------------------------------------------------
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

    // Suppression de la méthode en double et correction
    #[Route('/api/projects', name: 'api_projects', methods: ['GET'])]
    public function getProjects(ProjectRepository $projectRepository): JsonResponse
    {
        $projects = $projectRepository->findAll();

        $events = [];
        foreach ($projects as $project) {
            $events[] = [
                'id' => $project->getId(),
                'title' => $project->getTitre(),
                'start' => $project->getDateDebut()->format('Y-m-d'),
                'end' => $project->getDateFin()->format('Y-m-d'),
                'backgroundColor' => $this->getStatusColor($project->getStatut()),
                'borderColor' => $this->getStatusColor($project->getStatut()),
                'url' => $this->generateUrl('app_project_show', ['id' => $project->getId()]),
            ];
        }

        return new JsonResponse($events);
    }

    // Fonction auxiliaire pour définir la couleur selon le statut
    private function getStatusColor(string $status): string
    {
        return match($status) {
            'completed' => '#28a745', // vert
            'in_progress' => '#007bff', // bleu
            'on_hold' => '#ffc107', // jaune
            'not_started' => '#6c757d', // gris
            default => '#007bff', // bleu par défaut
        };
    }

    #[Route('/calendar', name: 'project_calendar', methods: ['GET'])]
    public function calendar(ProjectRepository $projectRepository): Response
    {
        // Récupérer les statistiques pour les passer à la vue
        $totalProjects = $projectRepository->count([]);
        $completedProjects = $projectRepository->count(['statut' => 'completed']);
        $inProgressProjects = $projectRepository->count(['statut' => 'in_progress']);
        $onHoldProjects = $projectRepository->count(['statut' => 'on_hold']);
        $notStartedProjects = $projectRepository->count(['statut' => 'not_started']);
        
        return $this->render('GestionProjet/project/calendar.html.twig', [
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'inProgressProjects' => $inProgressProjects,
            'onHoldProjects' => $onHoldProjects,
            'notStartedProjects' => $notStartedProjects,
        ]);
    }

    // Endpoint pour mettre à jour les dates d'un projet via AJAX
    #[Route('/update-dates', name: 'app_project_update_dates', methods: ['POST'])]
    public function updateDates(Request $request, ProjectRepository $projectRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);
        
        // Vérifier si les données nécessaires sont présentes
        if (!isset($data['id']) || !isset($data['dateDebut']) || !isset($data['dateFin'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Données incomplètes. ID, dateDebut et dateFin sont requis.'
            ], 400);
        }
        
        try {
            // Récupérer le projet
            $project = $projectRepository->find($data['id']);
            
            if (!$project) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Projet non trouvé.'
                ], 404);
            }
            
            // Mettre à jour les dates
            $project->setDateDebut(new \DateTime($data['dateDebut']));
            $project->setDateFin(new \DateTime($data['dateFin']));
            
            // Persister les changements
            $entityManager->flush();
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Dates du projet mises à jour avec succès.'
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }
}