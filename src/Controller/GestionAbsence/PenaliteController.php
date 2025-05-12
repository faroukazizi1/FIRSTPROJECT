<?php

namespace App\Controller\GestionAbsence;

use App\Entity\Penalite;
use App\Form\PenaliteType;
use App\Repository\AbsenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

class PenaliteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/penalite', name: 'app_penalite_index')]
    public function index(): Response
    {
        // Utilisation de l'EntityManager pour récupérer les pénalités
        $penalites = $this->entityManager->getRepository(Penalite::class)->findAll();

        return $this->render('GestionAbsence/penalite/index.html.twig', [
            'penalites' => $penalites,
        ]);
    }

    #[Route('/penalite/new', name: 'app_penalite_new')]
    public function new(Request $request, AbsenceRepository $absenceRepository): Response
    {
        // Récupère les CIN distincts des absences
        $cinChoices = $absenceRepository->createQueryBuilder('a')
            ->select('a.cin')
            ->distinct()
            ->getQuery()
            ->getResult();

        // Créer une liste de CIN pour le formulaire
        $cinList = array_combine(array_column($cinChoices, 'cin'), array_column($cinChoices, 'cin'));

        // Créer une nouvelle entité Penalite
        $penalite = new Penalite();

        // Créer le formulaire avec les CIN récupérés
        $form = $this->createForm(PenaliteType::class, $penalite, [
            'cin_choices' => $cinList,
        ]);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le CIN sélectionné
            $cin = $penalite->getCin();

            // CORRECTION: Récupérer la dernière absence pour ce CIN
            // pour obtenir son nombre d'absences (nbr_abs)
            $absence = $absenceRepository->createQueryBuilder('a')
                ->where('a.cin = :cin')
                ->setParameter('cin', $cin)
                ->orderBy('a.ID_abs', 'DESC')  // Prendre la plus récente
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $nbrAbs = 0;
            if ($absence) {
                // Récupérer le nombre d'absences depuis l'entité Absence
                $nbrAbs = $absence->getNbrAbs();
                $this->addFlash('info', "Nombre d'absences récupéré: $nbrAbs");
            } else {
                $this->addFlash('warning', "Aucune absence trouvée pour ce CIN.");
            }

            // Calcul du seuil d'absence
            $seuilAbs = (float)($nbrAbs / 2);
            $this->addFlash('info', "Seuil d'absence calculé: $seuilAbs");

            // Assigner le seuil à l'entité Penalite
            $penalite->setSeuilAbs($seuilAbs);

            // Vérification du seuil d'absence
            if ($seuilAbs >= 0) {
                try {
                    // Configuration et envoi du SMS via Twilio
                    $sid = 'AC0fced13fa813278ecaaa8552647b84b6'; // SID Twilio
                    $authToken = 'f207b0e2bcd3baeb211012cd78d76553'; // Token Twilio
                    $fromNumber = '+15737474358'; // Numéro Twilio


                    $client = new Client($sid, $authToken);

                    $message = "Le CIN numéro $cin a atteint le seuil d'absence de $seuilAbs.";

                    // Envoi du SMS
                    $client->messages->create(
                        '+21626404611',  // Remplace par le numéro du destinataire
                        [
                            'from' => $fromNumber,
                            'body' => $message,
                        ]
                    );

                    $this->addFlash('success', "Un SMS a été envoyé au numéro correspondant.");
                } catch (\Exception $e) {
                    // Capture et affiche les erreurs liées à Twilio
                    $this->addFlash('error', "Erreur lors de l'envoi du SMS: " . $e->getMessage());
                }
            }
            
            $this->addFlash('info', 'Penalite entity data: ' . print_r($penalite, true));
            // Persist l'entité Penalite avec le seuil calculé
            $this->entityManager->persist($penalite);
            $this->addFlash('info', 'Flushing to the database...');
            $this->entityManager->flush();

            // Rediriger vers la liste des pénalités
            return $this->redirectToRoute('app_penalite_index');
        }

        return $this->render('GestionAbsence/penalite/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/penalite/{ID_pen}', name: 'app_penalite_show', methods: ['GET'])]
    public function show(int $ID_pen): Response
    {
        // Récupérer la pénalité par son ID
        $penalite = $this->entityManager
            ->getRepository(Penalite::class)
            ->find($ID_pen);

        // Vérification si la pénalité existe
        if (!$penalite) {
            // Si la pénalité n'est pas trouvée, renvoyer une erreur 404
            throw $this->createNotFoundException('Pénalité non trouvée pour l\'ID ' . $ID_pen);
        }

        // Si la pénalité existe, retourner la vue
        return $this->render('GestionAbsence/penalite/show.html.twig', [
            'penalite' => $penalite,
        ]);
    }

    #[Route('/penalite/{ID_pen}/edit', name: 'app_penalite_edit')]
    public function edit(int $ID_pen, Request $request): Response
    {
        // Récupérer la pénalité par son ID
        $penalite = $this->entityManager
            ->getRepository(Penalite::class)
            ->find($ID_pen);

        if (!$penalite) {
            throw $this->createNotFoundException('Pénalité non trouvée');
        }

        // Créer le formulaire d'édition
        $form = $this->createForm(PenaliteType::class, $penalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            // Rediriger vers la liste des pénalités
            return $this->redirectToRoute('app_penalite_index');
        }

        return $this->render('GestionAbsence/penalite/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour récupérer le nombre d'absences par CIN
    #[Route('/penalite/nbr_abs/{cin}', name: 'app_penalite_nbr_abs')]
    public function getNbrAbsByCin(string $cin, AbsenceRepository $absenceRepository): Response
    {
        // CORRECTION: Récupérer la dernière absence pour ce CIN
        // pour obtenir son nombre d'absences (nbr_abs)
        $absence = $absenceRepository->createQueryBuilder('a')
            ->where('a.cin = :cin')
            ->setParameter('cin', $cin)
            ->orderBy('a.ID_abs', 'DESC')  // Prendre la plus récente
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $nbrAbs = 0;
        if ($absence) {
            // Récupérer le nombre d'absences depuis l'entité Absence
            $nbrAbs = $absence->getNbrAbs();
        }

        return $this->json(['nbr_abs' => $nbrAbs]);
    }

    #[Route('/penalite/search', name: 'app_penalite_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $cin = $request->query->get('cin', '');
        $sort = $request->query->get('sort', 'asc');
    
        // Chercher les pénalités correspondant au CIN et trier par seuil d'absence
        $queryBuilder = $this->entityManager->getRepository(Penalite::class)
            ->createQueryBuilder('p')
            ->where('p.cin LIKE :cin')
            ->setParameter('cin', '%'.$cin.'%');
    
        if ($sort === 'desc') {
            $queryBuilder->orderBy('p.seuilAbs', 'DESC');
        } else {
            $queryBuilder->orderBy('p.seuilAbs', 'ASC');
        }
    
        $penalites = $queryBuilder->getQuery()->getResult();
    
        // Renvoyer la liste filtrée et triée des pénalités
        return $this->render('GestionAbsence/penalite/_penalite_list.html.twig', [
            'penalites' => $penalites,
        ]);
    }

    #[Route('/penalite/{ID_pen}/delete', name: 'app_penalite_delete', methods: ['POST'])]
    public function delete(Request $request, int $ID_pen): Response
    {
        // Récupérer la pénalité par son ID
        $penalite = $this->entityManager
            ->getRepository(Penalite::class)
            ->find($ID_pen);
        
        if (!$penalite) {
            throw $this->createNotFoundException('Pénalité non trouvée pour l\'ID ' . $ID_pen);
        }

        // Vérification du token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete' . $penalite->getIDPen(), $request->request->get('_token'))) {
            // Suppression de la pénalité
            $this->entityManager->remove($penalite);
            $this->entityManager->flush();

            // Ajout d'un message flash de succès
            $this->addFlash('success', 'La pénalité a été supprimée avec succès.');
        } else {
            // Ajout d'un message flash d'erreur si le token est invalide
            $this->addFlash('error', 'Erreur lors de la suppression de la pénalité.');
        }

        // Redirection vers la liste des pénalités
        return $this->redirectToRoute('app_penalite_index', [], Response::HTTP_SEE_OTHER);
    }
}