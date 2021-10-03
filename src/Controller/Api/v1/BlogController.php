<?php


namespace App\Controller\Api\v1;


use App\Entity\TArticle;
use App\Entity\TCategorie;
use App\Entity\TComment;
use App\Repository\TArticleRepository;
use App\Repository\TCategorieRepository;
use App\Repository\TCommentRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller\Api\v1
 * @Route("/api/v1/blog", name="blog_")
 */
class BlogController extends AbstractController
{
    private Globals $globals;
    private TArticleRepository $articleRepo;
    private TCommentRepository $commentRepo;
    private TCategorieRepository $categorieRepo;

    public function __construct(Globals $globals, TArticleRepository $articleRepo, TCommentRepository $commentRepo, TCategorieRepository $categorieRepo)
    {
        $this->globals = $globals;
        $this->articleRepo = $articleRepo;
        $this->commentRepo = $commentRepo;
        $this->categorieRepo = $categorieRepo;
    }

    /**
     * @Route("/categories", name="categories", methods={"GET"})
     * @return JsonResponse
     */
    public function categorieList(): JsonResponse
    {
        return $this->globals->success([
            'categories' => array_map(function (TCategorie $categorie){
                return $categorie->tojson();
            }, $this->categorieRepo->findBy(['active' => true]))
        ]);
    }

    /**
     * @Route("/articles", name="articles", methods={"GET"})
     * @return JsonResponse
     */
    public function articles(): JsonResponse
    {
        return $this->globals->success([
            'articles' => array_map(function(TArticle $article){
                return $article->tojson();
            }, $this->articleRepo->findBy(['active' => true]))
        ]);
    }

    /**
     * @Route("/article", name="article", methods={"GET"})
     */
    public function article(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $article = $this->articleRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$article)
            return $this->globals->error(ErrorHttp::ARTICLE_NOT_FOUND);
        return $this->globals->success([
            'article' => $article->tojson()
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
}