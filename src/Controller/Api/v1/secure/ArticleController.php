<?php


namespace App\Controller\Api\v1\secure;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("articles", name="article")
     * @return JsonResponse
     */
    public function articles(): JsonResponse
    {
        return new JsonResponse([
            'articles' => []
        ]);
    }
}