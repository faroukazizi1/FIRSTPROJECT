<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $apiKey = "AIzaSyDQdsjlrFYKODaku3FSZ0fLC-pIckLY0lw";
        $userMessage = trim($request->request->get('message'));

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $userMessage]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($response, true);

        $botResponse = "Je ne comprends pas.";
        if (isset($decoded["candidates"][0]["content"]["parts"][0]["text"])) {
            $botResponse = $decoded["candidates"][0]["content"]["parts"][0]["text"];
        }

        return new JsonResponse(["bot" => $botResponse]);
    }
}
