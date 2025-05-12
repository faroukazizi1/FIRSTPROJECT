<?php
namespace App\Controller\GestionAbsence;

use App\Entity\Absence;
use App\Entity\Penalite;
use App\Entity\User; // Ajout de l'import pour l'entité User
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChatbotController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/chatbot', name: 'chatbot')]
    public function chatbot(Request $request): Response
    {
        // Récupère l'utilisateur actuellement authentifié
        $user = $this->getUser();
        
        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            // Rediriger vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Veuillez vous connecter pour accéder à cette page');
            return $this->redirectToRoute('app_login'); // Remplacez par votre route de connexion
        }
        
        // Récupère le CIN de l'utilisateur connecté
        $cin = $user->getCin(); // Assurez-vous que votre entité User a une méthode getCin()
        
        // Redirige vers la page des choix avec le CIN récupéré de l'utilisateur
        return $this->redirectToRoute('chatbot_choices');
    }

    #[Route('/chatbot/choices', name: 'chatbot_choices')]
    public function chatbotChoices(Request $request): Response
    {
        // Récupère l'utilisateur actuellement authentifié
        $user = $this->getUser();
        
        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page');
        }
        
        // Récupère le CIN de l'utilisateur connecté
        $cin = $user->getCin(); // Assurez-vous que votre entité User a une méthode getCin()

        // Définition des choix disponibles
        $choices = [
            'Le nombre d\'absences' => 1,
            'Le type de pénalité' => 2,
            'Le seuil de pénalité' => 3,
            'Toutes les informations' => 4,
            'Détection de fraudes' => 5,
            'Quitter' => 6,
        ];

        // Création du formulaire avec les choix
        $form = $this->createFormBuilder()
            ->add('choix', ChoiceType::class, [
                'choices' => $choices,
                'label' => false,
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'd-none'],
            ])
            ->getForm();

        $form->handleRequest($request);

        // Récupération des données
        $absenceRepository = $this->entityManager->getRepository(Absence::class);
        $penaliteRepository = $this->entityManager->getRepository(Penalite::class);

        $absences = $absenceRepository->findBy(['cin' => $cin]);
        $penalites = $penaliteRepository->findBy(['cin' => $cin]);

        // Préparation des données pour les réponses
        $absenceData = [
            'nbr_abs' => count($absences),
            'penalite_type' => $penalites ? $penalites[0]->getType() : 'Aucune pénalité',
            'seuil' => $penalites ? $penalites[0]->getSeuil_abs() : 'Non défini',
        ];

        if ($form->isSubmitted() && $form->isValid()) {
            $choix = $form->get('choix')->getData();
            $message = $this->generateResponse($choix, $absenceData, $absences);

            return $this->render('FrontOffice/chatbot/index.html.twig', [
                'message' => $message,
                'form' => $form->createView(),
                'cin' => $cin,
                'step' => 'choices',
                'choices' => $choices
            ]);
        }

        return $this->render('FrontOffice/chatbot/index.html.twig', [
            'form' => $form->createView(),
            'cin' => $cin,
            'step' => 'choices',
            'choices' => $choices
        ]);
    }

    /**
     * Génère la réponse en fonction du choix sélectionné
     */
    private function generateResponse(int $choix, array $absenceData, array $absences): string
    {
        switch ($choix) {
            case 1:
                return 'Le nombre d\'absences est : ' . $absenceData['nbr_abs'];
            case 2:
                return 'Le type de pénalité est : ' . $absenceData['penalite_type'];
            case 3:
                return 'Le seuil de pénalité est : ' . $absenceData['seuil'];
            case 4:
                return 'Toutes les informations : ' . 
                       '• Nombre d\'absences : ' . $absenceData['nbr_abs'] . ' ; ' . 
                       '• Type de pénalité : ' . $absenceData['penalite_type'] . ' ; ' . 
                       '• Seuil de pénalité : ' . $absenceData['seuil'];
            case 5:
                return $this->detectFraud($absences);
            case 6:
                return 'Merci d\'avoir utilisé notre service. À bientôt !';
            default:
                return 'Choix invalide. Veuillez sélectionner une option valide.';
        }
    }

    /**
     * Détecte les fraudes potentielles
     */
    private function detectFraud(array $absences): string
    {
        $fraudDates = array_filter($absences, function($absence) {
            $day = $absence->getDate()->format('N');
            return in_array($day, [1, 5]); // Lundi=1, Vendredi=5
        });

        if (count($fraudDates) > 0) {
            $dates = array_map(fn($a) => $a->getDate()->format('d/m/Y'), $fraudDates);
            return "⚠ Alerte : Fraude potentielle détectée les jours suivants : " . implode(', ', $dates);
        }

        return 'Aucune fraude détectée dans les absences.';
    }
}