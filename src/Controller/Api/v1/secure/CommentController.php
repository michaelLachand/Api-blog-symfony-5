<?php


namespace App\Controller\Api\v1\secure;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/comment")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class CommentController extends AbstractController
{

}