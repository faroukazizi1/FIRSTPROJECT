<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\Builder;

class TTwoFactorAuthController extends AbstractController
{
    #[Route('/2fa/setup', name: 'app_2fa_setup')]
    public function setup2FA(
        GoogleAuthenticatorInterface $googleAuthenticator,
        EntityManagerInterface $em
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getGoogleAuthenticatorSecret()) {
            $secret = $googleAuthenticator->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);
            $em->flush();
        }

        $qrContent = $googleAuthenticator->getQRContent($user);
        $qrImage = Builder::create()->data($qrContent)->size(300)->margin(10)->build();
        $qrImageUri = $qrImage->getDataUri();

        return $this->render('2fa/setup.html.twig', [
            'qrImage' => $qrImageUri,
        ]);
    }
}
