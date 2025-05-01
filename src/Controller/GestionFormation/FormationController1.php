<?php

namespace App\Controller\GestionFormation;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Formateur;
use App\Form\FormationSearchType;

use App\Service\MailService;




#[Route('/formation')]
final class FormationController1 extends AbstractController
{

    // src/Controller/FormationController.php
    #[Route('/', name: 'formation_index', methods: ['GET'])]
    public function index(Request $request, FormationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationSearchType::class);
        $form->handleRequest($request);
    
        // Debug: afficher les données du formulaire
        if ($form->isSubmitted()) {
            dump($form->getData());
        }
    
        $formations = $formationRepository->searchAndSort(
            $form->isSubmitted() ? $form->getData() : [],
            $request->query->get('sort')
        );
    
        // Debug: afficher les résultats
        dump($formations);
    
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
            'form' => $form->createView()
        ]);
    }

// Nouvelle route pour les statistiques
#[Route('/statistiques', name: 'app_formation_stats', methods: ['GET'])]
public function statistics(FormationRepository $formationRepository): Response
{
    $stats = [
        'total_formations' => $formationRepository->count([]),
        'formations_par_type' => $formationRepository->countByType(),
        'duree_moyenne' => $formationRepository->averageDuration(),
        'prochaines_formations' => $formationRepository->findUpcoming(5)
    ];

    return $this->render('formation/statistics.html.twig', [
        'stats' => $stats
    ]);
}



#[Route('/formation/new', name: 'app_formation_new')]
public function new(Request $request, EntityManagerInterface $em, MailService $mailService): Response
{
    $formation = new Formation();
    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($formation);
        $em->flush();

        // Envoi de mail au formateur
        $formateur = $formation->getFormateur();
        $mailService->envoyerMail(
            $formateur->getEmail(),
            'Nouvelle formation assignée',
            "Bonjour " . $formateur->getNomF() . ",\n\nVous êtes assigné à la formation : " . $formation->getTitre()
        );

        $this->addFlash('success', 'Formation ajoutée et mail envoyé.');

        return $this->redirectToRoute('formation_index');
    }

    return $this->render('formation/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id_form}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{id_form}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id_form}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId_form(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_index', [], Response::HTTP_SEE_OTHER);
    }
   

  
    
}
