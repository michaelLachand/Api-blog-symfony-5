<?php


namespace App\Controller\Api\v1\secure;


use App\Entity\TUser;
use App\Repository\TPaysRepository;
use App\Repository\TUserRepository;
use App\Shared\ErrorHttp;
use App\Shared\Globals;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Api\v1\secure
 * @Route("/api/v1/secure/user")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserController extends AbstractController
{
    private Globals $globals;
    private TUserRepository $userRepo;
    private TPaysRepository $paysRepo;

    public function __construct(Globals $globals, TUserRepository $userRepo, TPaysRepository $paysRepo)
    {
        $this->globals = $globals;
        $this->userRepo = $userRepo;
        $this->paysRepo = $paysRepo;
    }

    /**
     * @Route("/users", name="users", methods={"GET"})
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        return $this->globals->success([
            'users' => array_map(function(TUser $user){
                return $user->tojson();
            }, $this->userRepo->findAll())
        ]);
    }

    /**
     * @Route("/user", name="user", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);
        $user = $this->userRepo->findOneBy(['id' => $id]);

        if (!$user)
            return $this->globals->error(ErrorHttp::USER_NOT_FOUND);

        return $this->globals->success([
            'user' => $user->tojson()
        ]);
    }

    /**
     * @Route("/save", name="save", methods={"POST"})
     * @return JsonResponse
     */
    public function save(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset(
            $data->username,
            $data->firstname,
            $data->lastname,
            $data->fk_pays,
            $data->role
        )) return $this->globals->error(ErrorHttp::FORM_INVALID);

        if ($this->userRepo->findOneBy(['username' => $data->username]) !== null)
            return $this->globals->error(ErrorHttp::USERNAME_EXIST);

        $fk_pays = $this->paysRepo->findOneBy(['id' => $data->fk_pays, 'active' => true]);
        if (!$fk_pays)
            return $this->globals->error(ErrorHttp::PAYS_NOT_FOUND);

        if (!in_array($data->role, ['ROLE_ADMIN', 'ROLE_AUTHOR', 'ROLE_VISITEUR'], true))
            return $this->globals->error(ErrorHttp::ROLE_NOT_FOUND);

        $user = (new TUser())
            ->setActive(true)
            ->setUsername($data->username)
            ->setFirstname($data->firstname)
            ->setLastname($data->lastname)
            ->setFkPays($fk_pays)
            ->setRoles([$data->role])
            ->setPasswordToChange(true)
            ->setPasswordToken(uniqid('token-'));

        $user->setPassword($this->globals->encoder()->encodePassword($user, str_shuffle('qwertyuioasdfghjkxcvbnm234567890')));

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        // envoi du mail apres enregistrement de l'utilisateur
        return $this->globals->success($user->tojson());
    }

    /**
     * @Route("/update", name="update", methods={"PUT"})
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset(
            $data->id,
            $data->username,
            $data->firstname,
            $data->lastname,
            $data->fk_pays,
            $data->role,
            $data->initpassword
        )) return $this->globals->error(ErrorHttp::FORM_INVALID);

        $user = $this->userRepo->findOneBy(['id' => $data->id]);
        if (!$user)
            return $this->globals->error(ErrorHttp::USER_NOT_FOUND);

        $checkByUsername = $this->userRepo->findOneBy(['username' => $data->username]);
        if ($checkByUsername !==  null && $checkByUsername !== $user)
            return $this->globals->error(ErrorHttp::USERNAME_EXIST);

        $fk_pays = $this->paysRepo->findOneBy(['id' => $data->fk_pays, 'active' => true]);
        if (!$fk_pays)
            return $this->globals->error(ErrorHttp::PAYS_NOT_FOUND);

        if (!in_array($data->role, ['ROLE_ADMIN', 'ROLE_AUTHOR', 'ROLE_VISITEUR'], true))
            return $this->globals->error(ErrorHttp::ROLE_NOT_FOUND);

        $user->setActive(true)
            ->setUsername($data->username)
            ->setFirstname($data->firstname)
            ->setLastname($data->lastname)
            ->setFkPays($fk_pays)
            ->setRoles([$data->role])
            ->setPasswordToChange($data->initpassword === true)
            ->setPasswordToken($data->initpassword === true ? uniqid('token-') : null);

        if ($data->initpassword === true)
            $user->setPassword($this->globals->encoder()->encodePassword($user, str_shuffle('qwertyuioasdfghjkxcvbnm234567890')));

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        // envoi du mail apres mis a jour de l'utilisateur
        return $this->globals->success($user->tojson());
    }

    /**
     * @Route("/state", name="state", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function state(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        if (!$id)
            return $this->globals->error(ErrorHttp::PARAM_GET_NOT_FOUND);

        $user = $this->userRepo->findOneBy(['id' => $id]);
        if (!$user)
            return $this->globals->error(ErrorHttp::USER_NOT_FOUND);

        $user->setActive(!$user->getActive());
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success();
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

        $user = $this->userRepo->findOneBy(['id' => $id]);
        if (!$user)
            return $this->globals->error(ErrorHttp::USER_NOT_FOUND);

        $user->setActive(false);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->globals->success();
    }
}