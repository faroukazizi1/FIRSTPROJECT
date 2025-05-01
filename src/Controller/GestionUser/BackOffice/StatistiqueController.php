<?php

namespace App\Controller\GestionUser\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/back/Stats')]
final class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(
        UserRepository $userRepository, 
        PromotionRepository $promotionRepository, 
        ChartBuilderInterface $chartBuilder
    ): Response
    {
        // Statistiques des utilisateurs
        $totalUsers = $userRepository->count([]);
        $hommes = $userRepository->count(['sexe' => 'Homme']);
        $femmes = $userRepository->count(['sexe' => 'Femme']);
        $rhrCount = $userRepository->count(['role' => 'RHR']);
        $employeCount = $userRepository->count(['role' => 'Employe']);
    
        // Statistiques des promotions
        $totalPromotions = $promotionRepository->count([]);
        
        $promotions = $promotionRepository->findAll();
        $totalSalaire = 0;
        $promotionParType = [];
    
        foreach ($promotions as $promo) {
            $totalSalaire += $promo->getNouvSal();
    
            $type = $promo->getTypePromo();
            if (!isset($promotionParType[$type])) {
                $promotionParType[$type] = 0;
            }
            $promotionParType[$type]++;
        }
    
        $moyenneSalaire = $totalPromotions > 0 ? $totalSalaire / $totalPromotions : 0;

        // Créer les graphiques avec ChartBuilder
        $sexeChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $sexeChart->setData([
            'labels' => ['Hommes', 'Femmes'],
            'datasets' => [
                [
                    'label' => 'Répartition par sexe',
                    'data' => [$hommes, $femmes],
                    'backgroundColor' => ['#4e73df', '#1cc88a'],
                    'borderColor' => ['#4e73df', '#1cc88a'],
                    'borderWidth' => 1,
                ]
            ]
        ]);
        
        $roleChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $roleChart->setData([
            'labels' => ['Responsables RH', 'Employés'],
            'datasets' => [
                [
                    'label' => 'Répartition par rôle',
                    'data' => [$rhrCount, $employeCount],
                    'backgroundColor' => ['#f6c23e', '#36b9cc'],
                    'borderColor' => ['#f6c23e', '#36b9cc'],
                    'borderWidth' => 1,
                ]
            ]
        ]);
        
        $promoTypeChart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $promoTypeChart->setData([
            'labels' => array_keys($promotionParType),
            'datasets' => [
                [
                    'label' => 'Promotions par type',
                    'data' => array_values($promotionParType),
                    'backgroundColor' => '#4e73df',
                    'borderColor' => '#4e73df',
                    'borderWidth' => 1,
                ]
            ]
        ]);
        $promoTypeChart->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero' => true
                ]
            ]
        ]);

        return $this->render('statistique/index.html.twig', [
            'totalUsers' => $totalUsers,
            'hommes' => $hommes,
            'femmes' => $femmes,
            'rhrCount' => $rhrCount,
            'employeCount' => $employeCount,
            'totalPromotions' => $totalPromotions,
            'moyenneSalaire' => $moyenneSalaire,
            'promotionParType' => $promotionParType,
            'sexeChart' => $sexeChart,
            'roleChart' => $roleChart,
            'promoTypeChart' => $promoTypeChart,
        ]);
    }
}
