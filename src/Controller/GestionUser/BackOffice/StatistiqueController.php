<?php

namespace App\Controller\GestionUser\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;

#[Route('/back/Stats')]
final class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(
        UserRepository $userRepository, 
        PromotionRepository $promotionRepository,
        ChartBuilderInterface $chartBuilder // Injecting the ChartBuilderInterface
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

        // Creating a chart for Gender Distribution
        $genderChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $genderChart->setData([
            'labels' => ['Hommes', 'Femmes'],
            'datasets' => [
                [
                    'data' => [$hommes, $femmes],
                    'backgroundColor' => ['#4e73df', '#1cc88a'],
                    'borderColor' => ['#4e73df', '#1cc88a'],
                    'borderWidth' => 1,
                ],
            ],
        ]);
        $genderChart->setOptions([
            'responsive' => true,
        ]);
        if (!$genderChart) {
            throw new \Exception('Gender chart is not being rendered properly');
        }

        // Creating a chart for Role Distribution
        $roleChart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $roleChart->setData([
            'labels' => ['Responsables RH', 'Employés'],
            'datasets' => [
                [
                    'data' => [$rhrCount, $employeCount],
                    'backgroundColor' => ['#f6c23e', '#36b9cc'],
                    'borderColor' => ['#f6c23e', '#36b9cc'],
                    'borderWidth' => 1,
                ],
            ],
        ]);
        $roleChart->setOptions([
            'responsive' => true,
        ]);
        if (!$roleChart) {
            throw new \Exception('Role chart is not being rendered properly');
        }

        return $this->render('statistique/index.html.twig', [
            'totalUsers' => $totalUsers,
            'hommes' => $hommes,
            'femmes' => $femmes,
            'rhrCount' => $rhrCount,
            'employeCount' => $employeCount,
            'totalPromotions' => $totalPromotions,
            'moyenneSalaire' => $moyenneSalaire,
            'genderChart' => $genderChart, // Passing the chart to the template
            'roleChart' => $roleChart,     // Passing the chart to the template
        ]);
    }
}
