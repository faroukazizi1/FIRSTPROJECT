<?php

namespace App\Controller\GestionProjet;

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
        $project = new Project(); // Cree nouvel instance vide de l'entité Project.
        $form = $this->createForm(ProjectType::class, $project);  // Création form lié à l'entité Project.

        return $this->render('GestionProjet/project/index.html.twig', [
            'projects' => $projectRepository->findAll(), // Récupérer tous  projets.
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['POST'])]
    public function new(Request $request, ProjectRepository $projectRepository, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request); // Traitement des données de la requête (remplissage du formulaire avec les données POST).

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($project);//nhb ajoutei l'objet ($project) f bd, doctrine memoire 
                $entityManager->flush();// Exécute l'enregistrement en base
                $this->addFlash('success', 'Projet enregistré avec succès.');
                return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement du projet. Veuillez vérifier les champs.');
            }
        }

        return $this->render('GestionProjet/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
            'form' => $form->createView(),
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