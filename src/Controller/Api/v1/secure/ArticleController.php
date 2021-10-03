<?php


namespace App\Controller\Api\v1\secure;


use App\Entity\TArticle;
use App\Repository\TArticleRepository;
use App\Repository\TCategorieRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/article")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class ArticleController extends AbstractController
{
    private Globals $globals;
    private TArticleRepository $articleRepo;
    private TCategorieRepository $categorieRepo;

    public function __construct(Globals $globals, TArticleRepository $articleRepo, TCategorieRepository $categorieRepo)
    {
        $this->globals = $globals;
        $this->articleRepo = $articleRepo;
        $this->categorieRepo = $categorieRepo;
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
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
     * @Route("/list/own", name="list_own", methods={"GET"})
     * @return JsonResponse
     */
    public function articlesOwn(): JsonResponse
    {
        return $this->globals->success([
            'articles' => array_map(function(TArticle $article){
                return $article->tojson();
            }, $this->articleRepo->findBy(['active' => true, 'fk_user' => $this->getUser()]))
        ]);
    }

    /**
     * @Route("/find", name="article", methods={"GET"})
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
     * @Route("/save", name="save", methods={"POST"})
     */
    public function articleSave(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->title, $data->description, $data->fk_categorie_id))
            return $this->globals->error(ErrorHttp::FORM_INVALID);

        $categorie = $this->categorieRepo->findOneBy(['id' => $data->fk_categorie_id, 'active' => true]);
        if (!$categorie)
            return $this->globals->error(ErrorHttp::CATEGORIE_NOT_FOUND);

        $article = (new TArticle())
            ->setTitle($data->title)
            ->setDescription($data->description)
            ->setFkCategories($categorie)
            ->setFkUser($this->getUser())
            ->setDateSave(new \DateTime());

        try {
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
            return $this->globals->success([
                'article' => $article->tojson()
            ]);
        } catch (\Exception $exception){
            return $this->globals->error();
        }
    }

    /**
     * @Route("/update", name="update", methods={"PUT"})
     */
    public function articleUpdate(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->id, $data->title, $data->description, $data->fk_categorie_id))
            return $this->globals->error(ErrorHttp::FORM_INVALID);

        $article = $this->articleRepo->findOneBy(['id' => $data->id, 'active' => true, 'fk_user' => $this->getUser()]);
        if (!$article)
            return $this->globals->error(ErrorHttp::ARTICLE_NOT_FOUND);

        $categorie = $this->categorieRepo->findOneBy(['id' => $data->fk_categorie_id, 'active' => true]);
        if (!$categorie)
            return $this->globals->error(ErrorHttp::CATEGORIE_NOT_FOUND);

        $article->setTitle($data->title)
            ->setDescription($data->description)
            ->setFkCategories($categorie);

        try {
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
            return $this->globals->success([
                'article' => $article->tojson()
            ]);
        } catch (\Exception $exception){
            return $this->globals->error();
        }
    }

    /**
     * @Route("/delete", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $article = $this->articleRepo->findOneBy(['id' => $id, 'active' => true, 'fk_user' => $this->getUser()]);
        if (!$article)
            return $this->globals->error(ErrorHttp::ARTICLE_NOT_FOUND);

        $article->setActive(false);
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success();
    }
}