<?php

namespace App\Controller;

use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/statistiques', name: 'app_reponse_statistiques')]
public function index(ReponseRepository $reponseRepository): Response
{
    $reponses = $reponseRepository->findAll();

    // Logique de calcul des catégories
    $totalReponses = count($reponses);
    $courtePeriode = 0;
    $moyennePeriode = 0;
    $longuePeriode = 0;

    foreach ($reponses as $reponse) {
        $duree = $reponse->getDureeRemboursement();
        if ($duree < 12) {
            $courtePeriode++;
        } elseif ($duree >= 12 && $duree <= 24) {
            $moyennePeriode++;
        } else {
            $longuePeriode++;
        }
    }

    return $this->render('GestionFinance/reponse/statistique/index.html.twig', [
        'totalReponses' => $totalReponses,
        'courtePeriode' => $courtePeriode,
        'moyennePeriode' => $moyennePeriode,
        'longuePeriode' => $longuePeriode,
    ]);
}

}
