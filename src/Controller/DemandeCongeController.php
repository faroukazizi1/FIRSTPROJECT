<?php

namespace App\Controller;

use App\Entity\Demandeconge;
use App\Form\DemandecongeType;
use App\Repository\DemandecongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
#[Route('/demande-conge')]
final class DemandeCongeController extends AbstractController
{
    #[Route('/', name: 'app_demande_conge_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $action = $request->query->get('action');   // 'accept' ou 'refuse'
        $id = $request->query->get('id');           // ID de la demande
    
        if ($action && $id) {
            $demandeconge = $entityManager->getRepository(Demandeconge::class)->find($id);
    
            if ($demandeconge) {
                if ($action === 'accept') {
                    $demandeconge->setStatut('approuvé');
                    $this->addFlash('success', 'La demande a été acceptée.');
                } elseif ($action === 'refuse') {
                    $demandeconge->setStatut('refusé');
                    $this->addFlash('warning', 'La demande a été refusée.');
                }
                $entityManager->flush();
    
                return $this->redirectToRoute('app_demande_conge_index');
            }
        }
    
        // Partie affichage classique
        $cin = $request->query->get('cin');
        $sort = $request->query->get('sort', 'dateDebut');
    
        $qb = $entityManager->getRepository(Demandeconge::class)->createQueryBuilder('d');
    
        if ($cin) {
            $qb->andWhere('d.employeId = :cin')
               ->setParameter('cin', $cin);
        }
    
        $qb->orderBy('d.' . $sort, 'ASC');
        $demandeconges = $qb->getQuery()->getResult();
    
        return $this->render('demandeconge/index.html.twig', [
            'demandeconges' => $demandeconges,
        ]);
    }

#[Route('/new', name: 'app_demande_conge_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    $demandeconge = new Demandeconge();

    // Set default statut
    $demandeconge->setStatut('en_attente');

    $form = $this->createForm(DemandecongeType::class, $demandeconge);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $demandeconge->setDateDemande(new \DateTime());

        $entityManager->persist($demandeconge);
        $entityManager->flush();

        // Préparer l'email
        $email = (new Email())
            ->from('no-reply@flexirh.com')
            ->to('flexirh.equipe@gmail.com')
            ->subject('Nouvelle Demande de Congé')
            ->html("
                <h2>Nouvelle Demande de Congé</h2>
                <p><strong>Employé :</strong> {$demandeconge->getEmployeId()}</p>
                <p><strong>Date de début :</strong> {$demandeconge->getDateDebut()->format('Y-m-d')}</p>
                <p><strong>Date de fin :</strong> {$demandeconge->getDateFin()->format('Y-m-d')}</p>
<p><strong>Type :</strong> {$demandeconge->getTypeConge()}</p>
                <p><strong>Statut :</strong> {$demandeconge->getStatut()}</p>
                <p><strong>Date de demande :</strong> {$demandeconge->getDateDemande()->format('Y-m-d H:i')}</p>
            ");

        // Envoyer l'email
        $mailer->send($email);

        return $this->redirectToRoute('app_demande_conge_index');
    }

    return $this->render('demandeconge/new.html.twig', [
        'form' => $form,
    ]);
}



    #[Route('/{id}', name: 'app_demandeconge_show', methods: ['GET'])]
    public function show(Demandeconge $demandeconge): Response
    {
        return $this->render('demandeconge/show.html.twig', [
            'demandeconge' => $demandeconge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demandeconge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandeconge $demandeconge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandecongeType::class, $demandeconge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_demandeconge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandeconge/edit.html.twig', [
            'demandeconge' => $demandeconge,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_demandeconge_delete', methods: ['POST'])]
    public function delete(Request $request, Demandeconge $demandeconge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeconge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeconge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demandeconge_index', [], Response::HTTP_SEE_OTHER);
    }

}