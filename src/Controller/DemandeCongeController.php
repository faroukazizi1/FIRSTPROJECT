<?php

namespace App\Controller;

use App\Entity\Demandeconge;
use App\Form\DemandeCongeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeCongeController extends AbstractController
{
    #[Route('/demande-conge', name: 'app_demande_conge')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $demandes = $entityManager->getRepository(Demandeconge::class)->findAll();
        
        return $this->render('FrontOffice/demande_conge/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    #[Route('/demande-conge/new', name: 'app_demande_conge_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demande = new Demandeconge();
        $form = $this->createForm(DemandeCongeType::class, $demande);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Set additional data
            $demande->setDateDemande(new \DateTime());
            $demande->setStatut('En attente');
            
            $entityManager->persist($demande);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre demande de congé a été soumise avec succès.');
            return $this->redirectToRoute('app_demande_conge');
        }
        
        return $this->render('FrontOffice/demande_conge/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}