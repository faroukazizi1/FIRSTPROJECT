<?php

namespace App\Controller\GestionAbsence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home_front', name: 'app_home_front')]
    public function index(): Response
    {
        return $this->render('FrontOffice/Home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
