<?php

namespace App\Controller\GestionUser;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/GestionUser', name: 'app_user' , methods:['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('GestionUser/AfficherUser.html.twig' , [
            'users' => $userRepository->findAll(),
        ]);
    }
}
