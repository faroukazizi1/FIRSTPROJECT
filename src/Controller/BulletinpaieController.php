<?php

namespace App\Controller;

use App\Entity\Bulletinpaie;
use App\Form\BulletinpaieType;
use App\Repository\BulletinpaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
#[Route('/bulletinpaie')]
class BulletinPaieController extends AbstractController
{
    #[Route('', name: 'app_bulletinpaie_index', methods: ['GET'])]
    public function index(BulletinpaieRepository $bulletinpaieRepository): Response
    {
        return $this->render('bulletinpaie/index.html.twig', [
            'bulletinpaies' => $bulletinpaieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bulletinpaie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bulletinpaie = new Bulletinpaie();
        $form = $this->createForm(BulletinpaieType::class, $bulletinpaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Automatically set dateGeneration to the current date
            $bulletinpaie->setDateGeneration(new \DateTime());

            $entityManager->persist($bulletinpaie);
            $entityManager->flush();

            return $this->redirectToRoute('app_bulletinpaie_index');
        }

        return $this->render('bulletinpaie/new.html.twig', [
            'bulletinpaie' => $bulletinpaie,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'app_bulletinpaie_show', methods: ['GET'])]
    public function show(Bulletinpaie $bulletinpaie): Response
    {
        return $this->render('bulletinpaie/show.html.twig', [
            'bulletinpaie' => $bulletinpaie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bulletinpaie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bulletinpaie $bulletinpaie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BulletinpaieType::class, $bulletinpaie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // dateGeneration is set in the controller, do not modify it in the form.
            $entityManager->flush(); // Save the changes (without touching dateGeneration)
    
            return $this->redirectToRoute('app_bulletinpaie_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('bulletinpaie/edit.html.twig', [
            'bulletinpaie' => $bulletinpaie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_bulletinpaie_delete', methods: ['POST'])]
    public function delete(Request $request, Bulletinpaie $bulletinpaie, EntityManagerInterface $entityManager): Response
    {
        // Validate the CSRF token
        if ($this->isCsrfTokenValid('delete' . $bulletinpaie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bulletinpaie);
            $entityManager->flush();

            $this->addFlash('success', 'Bulletin supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Token CSRF invalide!');
        }

        // Redirect to the list after deletion
        return $this->redirectToRoute('app_bulletinpaie_index');
    }

    #[Route('/filter', name: 'app_bulletinpaie_filter', methods: ['GET'])]
    public function filterByDate(Request $request, BulletinpaieRepository $repository): Response
    {
        $month = $request->query->get('month', date('m'));
        $year = $request->query->get('year', date('Y'));
    
        $month = max(1, min(12, (int)$month));
        $year = max(2000, min(2100, (int)$year));
    
        $bulletins = $repository->findByMonthAndYear($month, $year);
    
        return $this->render('bulletinpaie/index.html.twig', [
            'bulletins' => $bulletins,
            'current_month' => $month,
            'current_year' => $year,
            'months' => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ],
            'years' => range(date('Y') - 5, date('Y') + 1)
        ]);
    }
    
}
