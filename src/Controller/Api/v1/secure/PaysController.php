<?php


namespace App\Controller\Api\v1\secure;


use App\Entity\TPays;
use App\Repository\TPaysRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PaysController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/pays", name="pays_ctrl_")
 * @Security("is_granted('ROLE_AUTHOR')")
 */
class PaysController extends AbstractController
{
    private Globals $globals;
    private TPaysRepository $paysRepo;

    public function __construct(Globals $globals, TPaysRepository $paysRepo)
    {
        $this->globals = $globals;
        $this->paysRepo = $paysRepo;
    }

    /**
     * @Route("/list", name="pays_list", methods={"GET"})
     * @return JsonResponse
     */
    public function paysList(): JsonResponse
    {
        return $this->globals->success([
            'pays_list' => array_map(function (TPays $pays){
                return $pays->tojson();
            }, $this->paysRepo->findBy(['active' => true]))
        ]);
    }

    /**
     * @Route("/find", name="pays_find_by_get_var", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function paysFindByIdOnGet(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $pays = $this->paysRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$pays)
            return $this->globals->error(ErrorHttp::PAYS_NOT_FOUND);
        return $this->globals->success([
            'pays' => $pays->tojson()
        ]);
    }

    /**
     * @Route("/find/{id}", name="pays_find_by_url_id", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function paysFindByIdOnUrl(int $id): JsonResponse
    {
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $pays = $this->paysRepo->findOneBy(['id' => $id, 'active' => true]);
        if (!$pays)
            return $this->globals->error(ErrorHttp::PAYS_NOT_FOUND);
        return $this->globals->success([
            'pays' => $pays->tojson()
        ]);
    }
}