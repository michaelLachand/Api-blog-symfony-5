<?php


namespace App\Controller\Api\v1\secure;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategorieController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/categorie")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class CategorieController extends AbstractController
{

}