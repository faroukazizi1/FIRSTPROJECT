<?php
namespace App\Controller;

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
    private $pretRepository;

    public function __construct(PretRepository $pretRepository)
    {
        $this->pretRepository = $pretRepository;
    }

    #[Route(name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PretRepository $pretRepository): Response
    {
        
        
        // Récupère les CIN distincts des pret
    $cinChoices = $pretRepository->createQueryBuilder('a')
    ->select('a.cin')
    ->distinct()
    ->getQuery()
    ->getResult();

// Créer une liste de CIN pour le formulaire
$cinList = array_combine(array_column($cinChoices, 'cin'), array_column($cinChoices, 'cin'));

// Créer une nouvelle entité Penalite
$reponse = new Reponse();
// Créer le formulaire avec les CIN récupérés
$form = $this->createForm(ReponseType::class, $reponse, [
    'cin_choices' => $cinList,
]);

$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{ID_reponse}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{ID_reponse}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager, PretRepository $pretRepository): Response
    {
        // Utilisation de la méthode correcte pour récupérer les CIN
        $cinList = $pretRepository->findDistinctCins();

        $form = $this->createForm(ReponseType::class, $reponse, [
            'cin_choices' => $cinList,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit.html.twig', [
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
}
