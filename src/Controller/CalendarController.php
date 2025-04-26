<?php
// src/Controller/CalendarController.php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function calendarPage(): Response
    {
        // Cette méthode affiche la page calendrier
        return $this->render('calendar.html.twig');
    }

    #[Route('/events.json', name: 'app_calendar_events')]
public function events(FormationRepository $formationRepository): JsonResponse
{
    $formations = $formationRepository->findAll();

    $events = [];

    foreach ($formations as $formation) {
        // Vérification si les dates sont non nulles
        $startDate = $formation->getDateD() ? $formation->getDateD()->format('Y-m-d\TH:i:s') : null;
        $endDate = $formation->getDateF() ? $formation->getDateF()->format('Y-m-d\TH:i:s') : null;

        // Si les deux dates sont valides, on les ajoute à l'événement
        if ($startDate && $endDate) {
            $events[] = [
                'title' => $formation->getTitre(),
                'start' => $startDate,
                'end' => $endDate,
            ];
        }
    }

    return new JsonResponse($events);
}

}
