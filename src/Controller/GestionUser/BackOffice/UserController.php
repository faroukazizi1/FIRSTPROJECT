<?php


namespace App\Controller\GestionUser\BackOffice;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use App\Service\GeocodingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{
    #[Route('/home_back/GestionUser', name: 'app_user_List', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        
        return $this->render('GestionUser/BackOffice/user/List.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/home_back/GestionUser/Ajax', name: 'user_ajax_search', methods: ['GET'])]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q', null);
        $role = $request->query->get('role');
        $sexe = $request->query->get('sexe');
    
        $users = $userRepository->searchUsers($query, $role, $sexe);
    
        return $this->render('GestionUser/BackOffice/user/AjaxSearch.html.twig', [
            'users' => $users,
        ]);
    }
    
    
    #[Route('/home_back/GestionUser/Ajout', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager ,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_List');
        }

        return $this->render('GestionUser/BackOffice/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/home_back/GestionUser/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_List');
    }

    #[Route('/home_back/GestionUser/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_List');
        }

        return $this->render('GestionUser/BackOffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}/map', name: 'user_show_map')]
    public function showMap(User $user, GeocodingService $geocodingService): Response
    {
        $coords = $geocodingService->geocode($user->getAdresse());

        if (!$coords) {
            throw $this->createNotFoundException('Could not geocode the address.');
        }

        return $this->render('GestionUser/BackOffice/user/map.html.twig', [
            'user' => $user,
            'coords' => $coords,
        ]);
    }

    #[Route('/home_back/GestionUser/export', name: 'app_user_export')]
    public function exportCsv(UserRepository $userRepository): Response
    {
        // Récupération des utilisateurs depuis la base de données (ajoute un filtre si nécessaire)
        $users = $userRepository->findAll();

        // Préparer la réponse
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="users.csv"');

        // Ouverture du flux pour écrire dans le fichier CSV
        $output = fopen('php://output', 'w');

        // Écriture des entêtes du fichier CSV
        fputcsv($output, ['ID', 'CIN', 'Nom', 'Prénom', 'Email', 'Adresse', 'Role'], ';'); // Séparateur semi-colon pour améliorer la lisibilité

        // Parcours des utilisateurs et écriture des données
        foreach ($users as $user) {
            fputcsv($output, [
                $user->getId(),
                $user->getCin(),
                $user->getNom(),
                $user->getPrenom(),
                $user->getEmail(),
                $user->getAdresse(),
                $user->getRole(),
            ], ';'); // Utilisation d'un séparateur spécifique
        }

        // Fermeture du flux après l'écriture
        fclose($output);

        return $response;
    }



}
