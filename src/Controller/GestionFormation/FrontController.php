<?php

namespace App\Controller\GestionFormation;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormateurRepository;
use App\Repository\TestRepository; 
use Symfony\Component\HttpFoundation\Request; // Ajoutez cette ligne
// <-- Ajoute le use nécessaire



class FrontController extends AbstractController
{
    #[Route('/front', name: 'front_formations')]
    public function showFront(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();

        return $this->render('front/formation_list.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/front/formation/{id}', name: 'front_formation_detail')]
    public function showFormation(int $id, FormationRepository $formationRepository): Response
    {
        // Récupérer la formation avec son formateur
        $formation = $formationRepository->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        // Récupérer le formateur associé à la formation
        $formateur = $formation->getFormateur();

        return $this->render('front/formation_detail.html.twig', [
            'formation' => $formation,
            'formateur' => $formateur,  // Passer l'objet formateur au template
        ]);
    }
    #[Route('/front/formateurs', name: 'front_formateurs')]
public function listFormateurs(FormateurRepository $formateurRepository): Response
{
    $formateurs = $formateurRepository->findAll();

    return $this->render('front/formateur_list.html.twig', [
        'formateurs' => $formateurs,
    ]);
}

#[Route('/front/formateur/{id}', name: 'front_formateur_detail')]
public function showFormateur(int $id, FormateurRepository $formateurRepository): Response
{
    $formateur = $formateurRepository->find($id);

    if (!$formateur) {
        throw $this->createNotFoundException('Formateur non trouvé.');
    }

    return $this->render('front/formateur_detail.html.twig', [
        'formateur' => $formateur,
    ]);
}
#[Route('/front/tests', name: 'front_tests')]
    public function showTests(): Response
    {
        // Données simulées pour les tests
        $tests = [
            [
                'id' => 1,
                'nom' => 'Test Soft Skills',
                'description' => 'Test de compétences comportementales',
                'duree' => 30,
                'questions' => [
                    'Question 1: Communication',
                    'Question 2: Teamwork',
                ],
            ],
            [
                'id' => 2,
                'nom' => 'Test Web Development',
                'description' => 'Test de développement web front-end',
                'duree' => 60,
                'questions' => [
                    'Question 1: HTML',
                    'Question 2: CSS',
                ],
            ],
            [
                'id' => 3,
                'nom' => 'Test Mobile Development',
                'description' => 'Test de développement mobile',
                'duree' => 45,
                'questions' => [
                    'Question 1: Android',
                    'Question 2: iOS',
                ],
            ],
        ];

        return $this->render('front/test_list.html.twig', [
            'tests' => $tests,
        ]);
    }

    #[Route('/front/test/{id}', name: 'front_test_detail')]
    public function showTestDetail(int $id, Request $request): Response
    {
        $testDetails = [
            1 => [
                'id' => 1, // Ajoutez cette ligne
        
                'nom' => 'Test Soft Skills',
                'description' => 'Test de compétences comportementales',
                'questions' => [
    [
        'question' => 'Question 1: Communication',
        'options' => ['a' => 'Savoir écouter', 'b' => 'Parler sans écouter'],
        'correct_answer' => 'a'
    ],
    [
        'question' => 'Question 2: Teamwork',
        'options' => ['a' => 'Faire tout seul', 'b' => 'Collaborer avec l\'équipe'],
        'correct_answer' => 'b'
    ],
]

            ],
            2 => [
                'id' => 2,
                'nom' => 'Test Web Development',
                'description' => 'Test de développement web front-end',
                'questions' => [
                    [
                        'question' => 'Question 1: HTML',
                        'options' => ['a' => '<p>', 'b' => '<div>', 'c' => '<style>'],
                        'correct_answer' => 'a'
                    ],
                    [
                        'question' => 'Question 2: CSS',
                        'options' => ['a' => 'JavaScript', 'b' => 'Cascading Style Sheets', 'c' => 'SQL'],
                        'correct_answer' => 'b'
                    ],
                ]
                ],
            3 => [
                'id' => 3,
                'nom' => 'Test Mobile Development',
                'description' => 'Test de développement mobile',
                'questions' => [
                    [
                        'question' => 'Question 1: Android',
                        'options' => ['a' => 'Java', 'b' => 'Swift', 'c' => 'Ruby'],
                        'correct_answer' => 'a'
                    ],
                    [
                        'question' => 'Question 2: iOS',
                        'options' => ['a' => 'Kotlin', 'b' => 'Swift', 'c' => 'C#'],
                        'correct_answer' => 'b'
                    ],
                ]
            ]
            
            
        ];
    
        $test = $testDetails[$id] ?? null;
    
        if (!$test) {
            throw $this->createNotFoundException('Test non trouvé.');
        }
    
        if ($request->isMethod('POST')) {
            $userAnswers = $request->get('answers');
            $correctAnswers = 0;
    
            foreach ($test['questions'] as $index => $question) {
                if (isset($userAnswers[$index]) && $userAnswers[$index] === $question['correct_answer']) {
                    $correctAnswers++;
                }
            }
            
    
            $totalQuestions = count($test['questions']);
            $percentage = ($correctAnswers / $totalQuestions) * 100;
            $resultMessage = $percentage >= 50 ? 'Félicitations, vous avez réussi!' : 'Désolé, vous n\'avez pas réussi.';
    
            return $this->render('front/test_result.html.twig', [
                'test' => $test,
                'score' => $correctAnswers,
                'totalQuestions' => $totalQuestions,
                'percentage' => $percentage,
                'resultMessage' => $resultMessage,
            ]);
        }
    
        return $this->render('front/test_detail.html.twig', [
            'test' => $test,
        ]);
    }
    

    #[Route('/front/test/{id}/evaluation', name: 'front_test_evaluation')]
    public function evaluateTest(int $id, Request $request): Response
    {
        // Définir des données simulées pour les tests et les questions
        $testDetails = [
            1 => [
                'nom' => 'Test Soft Skills',
                'questions' => [
                    'question1' => 'Communication',
                    'question2' => 'Teamwork',
                ]
            ],
            2 => [
                'nom' => 'Test Web Development',
                'questions' => [
                    'question1' => 'HTML',
                    'question2' => 'CSS',
                ]
            ],
            3 => [
                'nom' => 'Test Mobile Development',
                'questions' => [
                    'question1' => 'Android',
                    'question2' => 'iOS',
                ]
            ],
        ];

        $test = $testDetails[$id] ?? null;

        if (!$test) {
            throw $this->createNotFoundException('Test non trouvé.');
        }

        // Simuler un formulaire pour l'évaluation du test
        $form = $this->createFormBuilder()
            ->add('question1', null, ['label' => $test['questions']['question1']])
            ->add('question2', null, ['label' => $test['questions']['question2']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique de validation
            $data = $form->getData();
            $score = 0;
            if ($data['question1'] === 'b') $score++;
            if ($data['question2'] === 'a') $score++;

            $pourcentage = ($score / 2) * 100;

            if ($pourcentage >= 70) {
                $this->addFlash('success', 'Bravo ! Vous avez réussi le test.');
            } else {
                $this->addFlash('danger', 'Désolé, vous n\'avez pas validé.');
            }

            return $this->redirectToRoute('front_tests');
        }

        return $this->render('front/test_evaluation.html.twig', [
            'form' => $form->createView(),
            'test' => $test,
        ]);
    }
    #[Route('/front/test/{id}/submit', name: 'front_test_submit', methods: ['POST'])]
public function handleTestSubmission(int $id, Request $request): Response
{
    // Définir les bonnes réponses pour chaque test
    $correctAnswers = [
        1 => [ // Test Soft Skills
            0 => 'a', // Question 1
            1 => 'b'  // Question 2
        ],
        2 => [ // Test Web Development
            0 => 'a', // Question 1
            1 => 'b'  // Question 2
        ],
        3 => [ // Test Mobile Development
            0 => 'a', // Question 1
            1 => 'b'  // Question 2
        ]
    ];

    // Récupérer les réponses de l'utilisateur
    $userAnswers = $request->request->all('answers');
    $score = 0;

    // Vérifier chaque réponse
    foreach ($userAnswers as $index => $answer) {
        if (isset($correctAnswers[$id][$index]) && $answer === $correctAnswers[$id][$index]) {
            $score++;
        }
    }

    $totalQuestions = count($correctAnswers[$id]);
    $percentage = ($score / $totalQuestions) * 100;

    $resultMessage = $percentage >= 50 ? 'Félicitations, vous avez réussi !' : 'Désolé, vous n\'avez pas réussi.';

    // Vous devriez aussi récupérer les détails du test comme vous le faites dans showTestDetail
    $testDetails = [
        1 => [
            'nom' => 'Test Soft Skills',
            'description' => 'Test de compétences comportementales'
        ],
        2 => [
            'nom' => 'Test Web Development',
            'description' => 'Test de développement web front-end'
        ],
        3 => [
            'nom' => 'Test Mobile Development',
            'description' => 'Test de développement mobile'
        ]
    ];

    return $this->render('front/test_result.html.twig', [
        'test' => $testDetails[$id],
        'score' => $score,
        'totalQuestions' => $totalQuestions,
        'percentage' => $percentage,
        'resultMessage' => $resultMessage,
    ]);
}
    
}
