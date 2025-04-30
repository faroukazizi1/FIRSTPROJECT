<?php

namespace App\Controller\GestionFinance;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use App\Repository\PretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reponse')]
final class ReponseController extends AbstractController
{
    private PretRepository $pretRepository;

    public function __construct(PretRepository $pretRepository)
    {
        $this->pretRepository = $pretRepository;
    }

    #[Route(name: 'app_reponse_index', methods: ['GET'])]
    public function index(Request $request, ReponseRepository $reponseRepository): Response
    {
        $sort = $request->query->get('order[0][column]');
        $direction = $request->query->get('order[0][dir]', 'asc');
        $columns = [
            'dateReponse', 'montantDemande', 'revenusBruts', 'tauxInteret', 'mensualiteCredit',
            'potentielCredit', 'dureeRemboursement', 'montantAutorise', 'assurance'
        ];

        $queryBuilder = $reponseRepository->createQueryBuilder('r');

        // Si un tri est demandé, on applique le tri à la requête
        if ($sort !== null) {
            $sortColumn = $columns[$sort]; // Récupère le nom de la colonne
            $queryBuilder->orderBy('r.' . $sortColumn, $direction);
        }

        $reponses = $queryBuilder->getQuery()->getResult();

        if ($request->isXmlHttpRequest()) {
            return $this->render('GestionFinance/reponse/_reponse_list.html.twig', [
                'reponses' => $reponses,
            ]);
        }

        return $this->render('GestionFinance/reponse/index.html.twig', [
            'reponses' => $reponses,
        ]);
    }


    #[Route('/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cinChoices = $this->pretRepository->createQueryBuilder('a')
            ->select('a.cin')
            ->distinct()
            ->getQuery()
            ->getResult();

    $cinList = array_combine(array_column($cinChoices, 'cin'), array_column($cinChoices, 'cin'));

    $reponse = new Reponse();
    $form = $this->createForm(ReponseType::class, $reponse, [
            'cin_choices' => $cinList,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('GestionFinance/reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{ID_reponse}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('GestionFinance/reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{ID_reponse}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $cinChoices = $this->pretRepository->createQueryBuilder('a')
            ->select('a.cin')
            ->distinct()
            ->getQuery()
            ->getResult();

        $cinList = array_combine(array_column($cinChoices, 'cin'), array_column($cinChoices, 'cin'));

     $form = $this->createForm(ReponseType::class, $reponse, [
            'cin_choices' => $cinList,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('GestionFinance/reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{ID_reponse}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponse->getID_reponse(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search/by-duree', name: 'app_reponse_search_by_duree', methods: ['GET'])]
public function searchByDuree(Request $request, ReponseRepository $reponseRepository): Response
{
    $duree = $request->query->get('duree');
    
    $queryBuilder = $reponseRepository->createQueryBuilder('r');
    
    if ($duree !== null && $duree !== '') {
        $queryBuilder->where('r.Duree_remboursement LIKE :duree')
                     ->setParameter('duree', $duree . '%');
    }

    $reponses = $queryBuilder->getQuery()->getResult();

    return $this->render('GestionFinance/reponse/_reponse_list.html.twig', [
        'reponses' => $reponses,
    ]);
}

} 