<?php

namespace App\Controller\GestionUser\FrontOffice;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Snappy\Pdf;


final class PromotionController extends AbstractController
{
    #[Route('/GestionPromotion',name: 'app_promotion_index', methods: ['GET'])]
    public function List(PromotionRepository $promotionRepository): Response
    {
        $user = $this->getUser();
        return $this->render('GestionUser\FrontOffice\promotion\List.html.twig', [
            'promotions' => $promotionRepository->findPromotionById($user->getUserIdentifier()),
        ]);
    }

    #[Route('/GestionPromotion/{id}/pdf',name: 'promotion_pdf')]
    public function generatePdf(Pdf $knpSnappyPdf, PromotionRepository $promotionRepository, int $id): Response{
        
        $promotion = $promotionRepository->find($id);

        if(!$promotion) {
            throw $this->createNotFoundException('Promotion not found');
        }

        $html = $this->renderView('GestionUser\FrontOffice\promotion\pdf.html.twig', [
            'promotion' => $promotion
        ]);

        $pdfContent = $knpSnappyPdf->getOutputFromHtml($html);
        
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="promotion.pdf"'
            ]
        );
        
    }
}
