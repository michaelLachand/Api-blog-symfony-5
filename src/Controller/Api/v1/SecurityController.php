<?php


namespace App\Controller\Api\v1;


use App\Entity\TUser;
use App\Repository\TPaysRepository;
use App\Repository\TUserRepository;
use App\Shared\Globals;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private Globals $globals;
    private TPaysRepository $paysRepo;
    private TUserRepository $userRepo;

    public function __construct(Globals $globals, TPaysRepository $paysRepo, TUserRepository $userRepo)
    {
        $this->globals = $globals;
        $this->paysRepo = $paysRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/login", name="login", methods={"POST", "HEAD"})
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface $token
     * @return JsonResponse
     */
    public function login(UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $token): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset(
            $data->username,
            $data->password,
        )) return $this->globals->error('form invalid');

        $user = $this->userRepo->findOneBy(['username' => $data->username]);
        if (!$user) $this->globals->error('username not found');

        if (!$encoder->isPasswordValid($user, $data->password))
            return $this->globals->error('password invalid');

        return $this->globals->success(
            'success',
            [
                'username' => $user->getUsername(),
                'token' => $token->create($user)
            ]
        );
    }

    /**
     * @Route("/register", name="register", methods={"POST", "HEAD"})
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function register(UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $data = $this->globals->jsondecode();
        if (!isset(
            $data->username,
            $data->firstname,
            $data->lastname,
            $data->password,
            $data->fk_pays
        )) return $this->globals->error('form invalid');

        if ($this->userRepo->findOneBy(['username' => $data->username]) !== null)
            return $this->globals->error('username already exist');
        if (strlen($data->password) < 4)
            return $this->globals->error('password too short');

        $fk_pays = $this->paysRepo->findOneBy(['id' => $data->fk_pays, 'active' => true]);

        $user = (new TUser())
            ->setActive(true)
            ->setUsername($data->username)
            ->setFirstname($data->firstname)
            ->setLastname($data->lastname)
            ->setFkPays($fk_pays)
            ->setRoles(['ROLE_AUTHOR']);

        $user->setPassword($encoder->encodePassword($user, $data->password));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->globals->success('register done!', $user->tojson());
    }
}