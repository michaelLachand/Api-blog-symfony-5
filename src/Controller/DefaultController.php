<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return JsonResponse
     */
    public function home(): JsonResponse
    {
        return new JsonResponse('Bienvenue sur votre API Rest en symfony');
    }

    /**
     * @Route("/documentation", name="doc")
     * @return JsonResponse
     */
    public function apiDoc(): JsonResponse
    {
        return new JsonResponse([
            'title' => "Api Documentation REST Full",
            'sous-titre' => [
                "login" => "Rest login",
                "logout" => "Rest logout",
                "register" => "Rest register",
            ]
        ]);
    }
}