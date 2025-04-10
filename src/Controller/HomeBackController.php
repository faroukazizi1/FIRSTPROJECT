<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeBackController extends AbstractController
{
    #[Route('/home_back', name: 'app_home_back')]
    public function index(): Response
    {
        return $this->render('BackOffice/Home.html.twig', [
            'controller_name' => 'HomeBackController',
        ]);
    }
}
