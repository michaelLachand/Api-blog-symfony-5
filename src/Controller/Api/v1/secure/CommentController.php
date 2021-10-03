<?php


namespace App\Controller\Api\v1\secure;


use App\Entity\TComment;
use App\Repository\TArticleRepository;
use App\Repository\TCommentRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/comment")
 * @Security("is_granted('ROLE_VISITEUR')")
 */
class CommentController extends AbstractController
{
    private Globals $globals;
    private TArticleRepository $articleRepo;
    private TCommentRepository $commentRepo;

    public function __construct(Globals $globals, TArticleRepository $articleRepo, TCommentRepository $commentRepo)
    {
        $this->globals = $globals;
        $this->articleRepo = $articleRepo;
        $this->commentRepo = $commentRepo;
    }

    /**
     * @Route("/comments", name="comments", methods={"GET"})
     * @return JsonResponse
     */
    public function comments(): JsonResponse
    {
        return $this->globals->success([
            'comments' => array_map(function (TComment $comment){
                return $comment->tojson();
            }, $this->commentRepo->findBy(['active' => true]))
        ]);
    }

    /**
     * @Route("/comments/by/article", name="comment_by_article", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function commentByArticle(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);

        return $this->globals->success([
            'comments' => array_map(function(TComment $comment){
                return $comment->tojson();
            }, $this->commentRepo->findBy(['active' => true, 'fk_article' => $id]))
        ]);
    }

    /**
     * @Route("/new", name="comment_new", methods={"POST"})
     * @return JsonResponse
     */
    public function commentNew(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->fk_article, $data->comment))
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);

        $article = $this->articleRepo->findOneBy(['id' => $data->fk_article, 'active' => true]);
        if (!$article)
            return $this->globals->error(ErrorHttp::ARTICLE_NOT_FOUND);

        $comment = (new TComment())
            ->setDateSave(new \DateTime())
            ->setActive(true)
            ->setFkArticle($article)
            ->setComment($data->comment);
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success([
            'comment' => $comment->tojson()
        ]);
    }

    /**
     * @Route("/update", name="comment_update", methods={"PUT"})
     * @return JsonResponse
     */
    public function commentUpdate(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->id, $data->comment))
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);

        $comment = $this->commentRepo->findOneBy(['id' => $data->id, 'active' => true]);
        if (!$comment)
            return $this->globals->error(ErrorHttp::COMMENT_NOT_FOUND);

        $comment->setActive(true)
            ->setComment($data->comment);

        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success([
            'comment' => $comment->tojson()
        ]);
    }

    /**
     * @Route("/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);

        $comment = $this->commentRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$comment)
            return $this->globals->error(ErrorHttp::COMMENT_NOT_FOUND);

        $comment->setActive(false);
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success();
    }
}