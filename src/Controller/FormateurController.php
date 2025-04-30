<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use App\Form\FormateurSearchType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface; // ✅ Correct


#[Route('/formateur')]
final class FormateurController extends AbstractController
{
    #[Route(name: 'app_formateur_index', methods: ['GET', 'POST'])]
    public function index(Request $request, FormateurRepository $formateurRepository): Response
    {
        $form = $this->createForm(FormateurSearchType::class);
        $form->handleRequest($request);

        $sort = $request->query->get('sort', null); // 'asc' ou 'desc'
        $query = null;

        $qb = $formateurRepository->createQueryBuilder('f');

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $qb->where('f.Nom_F LIKE :q OR f.Prenom_F LIKE :q OR f.Specialite LIKE :q')
               ->setParameter('q', '%' . $query . '%');
        }

        if ($sort === 'asc' || $sort === 'desc') {
            $qb->orderBy('f.Specialite', $sort);
        }

        $formateurs = $qb->getQuery()->getResult();

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
            'search_form' => $form->createView(),
            'current_sort' => $sort,
        ]);
    }

    #[Route('/new', name: 'app_formateur_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $entityManager,
    HttpClientInterface $client
): Response {
    $veriphoneApiKey = $_ENV['VERIPHONE_API_KEY']; // 🛠️ Lecture directe depuis le .env

    $formateur = new Formateur();
    $form = $this->createForm(FormateurType::class, $formateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le numéro
        $numero = $formateur->getNumero(); // (attention : assure-toi que getNumero() existe)

        // Appeler Veriphone
        $response = $client->request('GET', 'https://api.veriphone.io/v2/verify', [
            'query' => [
                'phone' => $numero,
                'key' => $veriphoneApiKey
            ]
        ]);

        $data = $response->toArray(false); // false pour éviter exceptions automatiques

        // Vérifier la réponse
        if (isset($data['phone_valid']) && !$data['phone_valid']) {
            $this->addFlash('error', 'Numéro invalide selon Veriphone.');
            return $this->redirectToRoute('app_formateur_new');
        }

        // Si OK, enregistrer
        $entityManager->persist($formateur);
        $entityManager->flush();

        $this->addFlash('success', 'Formateur ajouté avec succès.');
        return $this->redirectToRoute('app_formateur_index');
    }

    return $this->render('formateur/new.html.twig', [
        'formateur' => $formateur,
        'form' => $form,
    ]);
}


    #[Route('/{idFormateur}', name: 'app_formateur_show', methods: ['GET'])]

public function show(Formateur $formateur): Response
    
    {
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur,
        ]);
    }
    #[Route('/{idFormateur}/edit', name: 'app_formateur_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Formateur $formateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormateurType::class, $formateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formateur_index');
        }

        return $this->render('formateur/edit.html.twig', [
            'formateur' => $formateur,
            'form' => $form,
        ]);
    }

    #[Route('/{idFormateur}/delete', name: 'app_formateur_delete', methods: ['POST'])]
    public function delete(Request $request, Formateur $formateur, EntityManagerInterface $entityManager): Response
    {
        if (count($formateur->getFormations()) > 0) {
            $this->addFlash('error', 'Ce formateur est encore assigné à une ou plusieurs formations. Veuillez d\'abord modifier ou supprimer ces formations.');
            return $this->redirectToRoute('app_formateur_index');
        }
    
        if ($this->isCsrfTokenValid('delete' . $formateur->getIdFormateur(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($formateur);
            $entityManager->flush();
            $this->addFlash('success', 'Formateur supprimé avec succès.');
        }
    
        return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
