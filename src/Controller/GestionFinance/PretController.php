<?php

namespace App\Controller\GestionFinance;

use App\Entity\Pret;
use App\Form\PretType;
use App\Repository\PretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Twilio\Rest\Client;

#[Route('/pret')]
final class PretController extends AbstractController
{
    #[Route(name: 'app_pret_index', methods: ['GET'])]
    public function index(PretRepository $pretRepository): Response
    {
        return $this->render('GestionFinance/pret/index.html.twig', [
            'prets' => $pretRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pret_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pret = new Pret();
        $form = $this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        $simulation = null;

        if ($form->isSubmitted() && $request->request->has('simuler')) {
            // Simulation sans sauvegarde
            $revenus = $pret->getRevenusBruts();
            $age = $pret->getAgeEmploye();
            $duree = $pret->getDureeRemboursement();
            $montant = $pret->getMontantPret();

            $montantMax = $revenus * 10;
            $taux = ($age < 30) ? 3.5 : 5.0;
            $interets = $montant * $taux / 100;
            $mensualite = ($montant + $interets) / $duree;

            $eligible = $mensualite < ($revenus * 0.4);

            $simulation = [
                'montantMax' => $montantMax,
                'taux' => $taux,
                'mensualite' => round($mensualite, 2),
                'eligible' => $eligible ? 'Oui' : 'Non',
            ];
        }

        if ($form->isSubmitted() && $form->isValid() && $request->request->has('submit')) {
            $entityManager->persist($pret);
            $entityManager->flush();

            // Envoi SMS via Twilio
           // Envoi SMS via Twilio
           $sid = ''; // Ton SID Twilio
           $authToken = ''; // Ton Auth Token Twilio
           $fromNumber = ''; // Ton numéro Twilio
           $toNumber = ''; // Numéro du destinataire
            $client = new Client($sid, $authToken);
            $message = "Votre demande avec le CIN " . $pret->getCin() . " est prise en considération.";

            try {
                $client->messages->create($toNumber, [
                    'from' => $fromNumber,
                    'body' => $message,
                ]);
                $this->addFlash('success', "Le prêt a été ajouté et un SMS de confirmation a été envoyé.");
            } catch (\Exception $e) {
                $this->addFlash('warning', "Le prêt a été ajouté, mais l'envoi du SMS a échoué.");
            }

            return $this->redirectToRoute('app_pret_index');
        }

        return $this->render('GestionFinance/pret/new.html.twig', [
            'pret' => $pret,
            'form' => $form->createView(),
            'simulation' => $simulation,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_pret_show', methods: ['GET'])]
    public function show(int $id, PretRepository $pretRepository): Response
    {
        $pret = $pretRepository->find($id);

        if (!$pret) {
            throw $this->createNotFoundException('Prêt non trouvé.');
        }

        return $this->render('GestionFinance/pret/show.html.twig', [
            'pret' => $pret,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_pret_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, PretRepository $pretRepository, EntityManagerInterface $entityManager): Response
    {
        $pret = $pretRepository->find($id);

        if (!$pret) {
            throw $this->createNotFoundException('Prêt non trouvé.');
        }

        $form = $this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_pret_index');
        }

        return $this->render('GestionFinance/pret/edit.html.twig', [
            'pret' => $pret,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_pret_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, PretRepository $pretRepository, EntityManagerInterface $entityManager): Response
    {
        $pret = $pretRepository->find($id);

        if (!$pret) {
            throw $this->createNotFoundException('Prêt non trouvé.');
        }

        if ($this->isCsrfTokenValid('delete' . $pret->getId_pret(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pret);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pret_index');
    }

    #[Route('/calendar', name: 'pret_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        return $this->render('GestionFinance/pret/calendar.html.twig');
    }

    #[Route('/api/prets', name: 'api_prets', methods: ['GET'])]
    public function apiPrets(PretRepository $pretRepository): JsonResponse
    {
        $prets = $pretRepository->findAll();
        $events = [];

        foreach ($prets as $pret) {
            // Obtenir la date et la formater correctement
            $datePret = $pret->getDatePret();
            $dateFormatted = $datePret->format('Y-m-d');
            
            // Pour la vue semaine, ajouter une heure aléatoire entre 9h et 17h
            $hour = rand(9, 17);
            $minute = rand(0, 5) * 10; // minutes par pas de 10 (0, 10, 20, 30, 40, 50)
            $dateTimeFormatted = $dateFormatted . 'T' . sprintf('%02d:%02d:00', $hour, $minute);
            
            // Calculer les informations financières pour l'affichage
            $montant = $pret->getMontantPret();
            $taux = $pret->getTaux();
            $duree = $pret->getDureeRemboursement();
            
            // Calculer les intérêts et le montant total
            $interets = $montant * $taux / 100;
            $montantTotal = $montant + $interets;
            $mensualite = $montantTotal / ($duree * 12);
            
            // Définir une couleur basée sur le montant du prêt
            if ($montant < 10000) {
                $backgroundColor = '#4CAF50'; // vert pour les petits montants
            } elseif ($montant < 50000) {
                $backgroundColor = '#FF9800'; // orange pour les montants moyens
            } else {
                $backgroundColor = '#F44336'; // rouge pour les gros montants
            }
            
            $events[] = [
                'id' => $pret->getID_pret(),
                'title' => 'Montant: ' . number_format($pret->getMontantPret(), 2) . ' €',
                'start' => $dateTimeFormatted, // Date avec heure pour la vue semaine
                'url' => $this->generateUrl('pret_details', ['id' => $pret->getID_pret()]),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'extendedProps' => [
                    'cin' => $pret->getCin(),
                    'revenus' => $pret->getRevenusBruts(),
                    'age' => $pret->getAgeEmploye(),
                    'categorie' => $pret->getCategorie(),
                    'mensualite' => round($mensualite, 2),
                    'duree' => $duree,
                    'taux' => $taux
                ]
            ];
        }

        return $this->json($events);
    }

    #[Route('/api/update-date', name: 'api_update_date', methods: ['POST'])]
    public function updateDate(Request $request, EntityManagerInterface $entityManager, PretRepository $pretRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['id']) || !isset($data['newDate'])) {
            return $this->json(['success' => false, 'message' => 'Données manquantes'], 400);
        }
        
        $pretId = $data['id'];
        
        // Correction du décalage de timezone
        $newDate = new \DateTime($data['newDate']);
        $newDate->setTime(12, 0, 0); // On fixe l'heure à midi pour éviter les problèmes de timezone
        
        $pret = $pretRepository->find($pretId);
        
        if (!$pret) {
            return $this->json(['success' => false, 'message' => 'Prêt non trouvé'], 404);
        }
        
        $pret->setDatePret($newDate);
        $entityManager->flush();
        
        return $this->json(['success' => true, 'date' => $newDate->format('Y-m-d')]);
    }

    #[Route('/details/{id<\d+>}', name: 'pret_details', methods: ['GET'])]
    public function details(int $id, PretRepository $pretRepository): Response
    {
        $pret = $pretRepository->find($id);

        if (!$pret) {
            throw $this->createNotFoundException('Prêt non trouvé.');
        }

        return $this->render('GestionFinance/pret/details.html.twig', [
            'pret' => $pret,
        ]);
    }
}