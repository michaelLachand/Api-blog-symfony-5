<?php


namespace App\Controller\Api\v1\secure;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/user")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class UserController extends AbstractController
{

}