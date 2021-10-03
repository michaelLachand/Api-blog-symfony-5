<?php


namespace App\Controller\Api\v1\secure;


use App\Entity\TCategorie;
use App\Repository\TCategorieRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategorieController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/categorie", name="categorie_ctrl_")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class CategorieController extends AbstractController
{
    private Globals $globals;
    private TCategorieRepository $categorieRepo;

    public function __construct(Globals $globals, TCategorieRepository $categorieRepo)
    {
        $this->globals = $globals;
        $this->categorieRepo = $categorieRepo;
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
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
     * @Route("/find", name="find")
     * @param Request $request
     * @return JsonResponse
     */
    public function categorie(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $categorie = $this->categorieRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$categorie)
            return $this->globals->error(ErrorHttp::CATEGORIE_NOT_FOUND);
        return $this->globals->success([
            'categorie' => $categorie->tojson()
        ]);
    }

    /**
     * @Route("/save", name="save", methods={"POST"})
     */
    public function categorieSave(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->titre, $data->description))
            return $this->globals->error(ErrorHttp::FORM_INVALID);
        $categorie = (new TCategorie())
            ->setTitre($data->titre)
            ->setDescription($data->description)
            ->setDateSave(new \DateTime());
        try {
            $this->getDoctrine()->getManager()->persist($categorie);
            $this->getDoctrine()->getManager()->flush();
            return $this->globals->success([
                'categorie' => $categorie->tojson()
            ]);
        } catch (\Exception $exception){
            return $this->globals->error();
        }
    }

    /**
     * @Route("/update", name="update", methods={"PUT"})
     */
    public function categorieUpdate(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset($data->id, $data->titre, $data->description))
            return $this->globals->error(ErrorHttp::FORM_INVALID);

        $categorie = $this->categorieRepo->findOneBy(['id' => $data->id, 'active' => true]);
        if (!$categorie)
            return $this->globals->error(ErrorHttp::CATEGORIE_NOT_FOUND);

        $categorie->setTitre($data->titre)
            ->setDescription($data->description);

        try {
            $this->getDoctrine()->getManager()->persist($categorie);
            $this->getDoctrine()->getManager()->flush();
            return $this->globals->success([
                'categorie' => $categorie->tojson()
            ]);
        } catch (\Exception $exception){
            return $this->globals->error();
        }
    }

    /**
     * @Route("/delete", name="delete", methods={"DELETE"})
     */
    public function categorieDelete(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $categorie = $this->categorieRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$categorie)
            return $this->globals->error(ErrorHttp::CATEGORIE_NOT_FOUND);

        $categorie->setActive(false);
        $this->getDoctrine()->getManager()->persist($categorie);
        $this->getDoctrine()->getManager()->flush();

        return $this->globals->success();
    }
}