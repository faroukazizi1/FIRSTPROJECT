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
            $events[] = [
                'title' => 'Revenus: ' . $pret->getRevenusBruts(),
                'start' => $pret->getDatePret()->format('Y-m-d'),
                'url' => $this->generateUrl('app_pret_show', ['id' => $pret->getId_pret()]),
            ];
        }

        return $this->json($events);
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
