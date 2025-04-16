<?php

namespace App\Controller;

use App\Entity\Pret;
use App\Form\PretType;
use App\Repository\PretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twilio\Rest\Client;

#[Route('/pret')]
final class PretController extends AbstractController
{
    #[Route(name: 'app_pret_index', methods: ['GET'])]
    public function index(PretRepository $pretRepository): Response
    {
        return $this->render('pret/index.html.twig', [
            'prets' => $pretRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pret_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pret = new Pret();
        $form = $this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pret);
            $entityManager->flush();

            // Configuration Twilio
            $sid = ''; // Ton SID Twilio
            $authToken = ''; // Ton Auth Token Twilio
            $fromNumber = ''; // Ton numéro Twilio
            $toNumber = ''; // Numéro du destinataire

            $client = new Client($sid, $authToken);

            // Message personnalisé avec le numéro de CIN
            $message = "Votre demande avec le CIN " . $pret->getCin() . " est prise en considération.";

            try {
                $client->messages->create(
                    $toNumber,
                    [
                        'from' => $fromNumber,
                        'body' => $message,
                    ]
                );
                $this->addFlash('success', "Le prêt a été ajouté et un SMS de confirmation a été envoyé.");
            } catch (\Exception $e) {
                $this->addFlash('warning', "Le prêt a été ajouté, mais l'envoi du SMS a échoué.");
            }

            return $this->redirectToRoute('app_pret_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pret/new.html.twig', [
            'pret' => $pret,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_pret_show', methods: ['GET'])]
    public function show(Pret $pret): Response
    {
        return $this->render('pret/show.html.twig', [
            'pret' => $pret,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pret_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pret $pret, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pret_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pret/edit.html.twig', [
            'pret' => $pret,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_pret_delete', methods: ['POST'])]
    public function delete(Request $request, Pret $pret, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pret->getId_pret(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pret);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pret_index', [], Response::HTTP_SEE_OTHER);
    }
}
