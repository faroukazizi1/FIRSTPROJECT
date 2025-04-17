<?php

namespace App\Controller\GestionFormation;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormateurRepository;


class FrontController extends AbstractController
{
    #[Route('/front', name: 'front_formations')]
    public function showFront(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();

        return $this->render('front/formation_list.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/front/formation/{id}', name: 'front_formation_detail')]
    public function showFormation(int $id, FormationRepository $formationRepository): Response
    {
        // Récupérer la formation avec son formateur
        $formation = $formationRepository->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        // Récupérer le formateur associé à la formation
        $formateur = $formation->getFormateur();

        return $this->render('front/formation_detail.html.twig', [
            'formation' => $formation,
            'formateur' => $formateur,  // Passer l'objet formateur au template
        ]);
    }
    #[Route('/front/formateurs', name: 'front_formateurs')]
public function listFormateurs(FormateurRepository $formateurRepository): Response
{
    $formateurs = $formateurRepository->findAll();

    return $this->render('front/formateur_list.html.twig', [
        'formateurs' => $formateurs,
    ]);
}

#[Route('/front/formateur/{id}', name: 'front_formateur_detail')]
public function showFormateur(int $id, FormateurRepository $formateurRepository): Response
{
    $formateur = $formateurRepository->find($id);

    if (!$formateur) {
        throw $this->createNotFoundException('Formateur non trouvé.');
    }

    return $this->render('front/formateur_detail.html.twig', [
        'formateur' => $formateur,
    ]);
}
    

}
