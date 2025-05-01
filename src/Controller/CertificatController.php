<?php

// src/Controller/CertificatController.php
namespace App\Controller;

use App\Entity\Formation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CertificatController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/generate-certificate', name: 'generate_certificate', methods: ['GET', 'POST'])]
    public function generateCertificate(Request $request, MailerInterface $mailer): Response
    {
        // Récupérer les utilisateurs et les formations depuis la base de données
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $formations = $this->entityManager->getRepository(Formation::class)->findAll();

        // Si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer l'ID de l'utilisateur et de la formation sélectionnés
            $userId = $request->get('user_id');
            $formationId = $request->get('formation_id');

            // Récupérer les entités utilisateur et formation
            $user = $this->entityManager->getRepository(User::class)->find($userId);
            $formation = $this->entityManager->getRepository(Formation::class)->find($formationId);

            if (!$user || !$formation) {
                // Si l'utilisateur ou la formation n'existe pas
                $this->addFlash('error', 'Utilisateur ou formation non trouvé');
                return $this->redirectToRoute('generate_certificate'); // Reste sur la même page
            }

            // Génération du certificat en PDF
            $certificateContent = $this->renderView('certificat/certificate.html.twig', [
                'user' => $user,
                'formation' => $formation,
            ]);

            // Création de l'instance Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($certificateContent);

            // Définir les options et la taille du papier
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);  // Activer l'exécution PHP si nécessaire
            $dompdf->setOptions($options);
            $dompdf->setPaper('A4', 'portrait');

            // Utiliser une police qui prend en charge UTF-8
            $dompdf->getOptions()->set('defaultFont', 'DejaVuSans');

            // Rendu du PDF
            $dompdf->render();
            $output = $dompdf->output();
            $pdfFilename = 'certificat_' . $user->getUsername() . '_' . $formation->getTitre() . '.pdf';

            // Vérifier si le répertoire existe et a les bonnes permissions
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/certificates/' . $pdfFilename;
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0775, true);
            }

            // Sauvegarder le PDF
            file_put_contents($pdfPath, $output);

           
            // Ne pas rediriger, mais rester sur la même page
            return $this->render('certificat/generate_certificate.html.twig', [
                'users' => $users,
                'formations' => $formations,
            ]);
        }

        // Afficher le formulaire pour générer un certificat
        return $this->render('certificat/generate_certificate.html.twig', [
            'users' => $users,
            'formations' => $formations,
        ]);
    }
}
