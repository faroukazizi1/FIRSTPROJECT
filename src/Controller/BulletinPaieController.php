<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use App\Entity\Bulletinpaie;
use App\Form\BulletinpaieType;
use App\Repository\BulletinpaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
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
    public function new(Request $request, EntityManagerInterface $entityManager, \Twig\Environment $twig, MailerInterface $mailer): Response
    {
        $bulletinpaie = new Bulletinpaie();
        $form = $this->createForm(BulletinpaieType::class, $bulletinpaie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $salaireBrut = $bulletinpaie->getSalaireBrut();
            $deductions = 200;
            $impot = $this->calculerImpot($salaireBrut);
            $salaireNet = $salaireBrut - $deductions - $impot;
    
            $bulletinpaie->setDeductions($deductions);
            $bulletinpaie->setSalaireNet(round($salaireNet, 2));
            $bulletinpaie->setDateGeneration(new \DateTime());
    
            $entityManager->persist($bulletinpaie);
            $entityManager->flush();
    
            // 🎨 Génération du HTML via Twig
            $html = $twig->render('bulletinpaie/pdf.html.twig', [
                'bulletin' => $bulletinpaie
            ]);
    
            // ⚙️ Configuration Dompdf
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            // Sauvegarder temporairement le PDF
            $pdfOutput = $dompdf->output();
            $pdfPath = sys_get_temp_dir() . '/Bulletin_Paie_'.$bulletinpaie->getId().'.pdf';
            file_put_contents($pdfPath, $pdfOutput);
    
            // 🔹 Récupérer l'email de l'employé (à adapter selon ta structure)
            // Exemple simple :
            $employeEmail = $bulletinpaie->getEmploye()->getEmail();
    
            // ✉️ Création de l'email
            $email = (new Email())
                ->from('admin@entreprise.com')
                ->to($employeEmail)
                ->subject('Votre Bulletin de Paie')
                ->text('Bonjour, veuillez trouver ci-joint votre bulletin de paie.')
                ->attachFromPath($pdfPath);
    
            // 🚀 Envoi de l'email
            $mailer->send($email);
    
            // Option : supprimer le fichier temporaire
            unlink($pdfPath);
    
            $this->addFlash('success', 'Bulletin généré et envoyé par mail.');
    
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


    private function calculerImpot($salaireBrut) {
        $tranches = [
            [0, 5000, 0],
            [5000.01, 10000, 0.15],
            [10000.01, 20000, 0.25],
            [20000.01, 30000, 0.30],
            [30000.01, 40000, 0.33],
            [40000.01, 50000, 0.36],
            [50000.01, 70000, 0.38],
            [70000.01, PHP_INT_MAX, 0.40]
        ];
    
        $impot = 0;
        $restant = $salaireBrut;
    
        foreach ($tranches as $tranche) {
            list($min, $max, $taux) = $tranche;
    
            if ($salaireBrut > $min) {
                $base = min($restant, $max - $min);
                $impot += $base * $taux;
                $restant -= $base;
            }
    
            if ($restant <= 0) break;
        }
    
        return $impot;
    }
    
    
}
