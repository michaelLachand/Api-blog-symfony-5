<?php


namespace App\Shared;


use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Globals
{
    private UserPasswordEncoderInterface $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function jsondecode()
    {
        try {
            return file_get_contents('php://input') ?
                json_decode(file_get_contents('php://input'), false) : [];
        } catch (Exception $e){
            return [];
        }
    }

    public function encoder(): UserPasswordEncoderInterface
    {
        return $this->encoder;
    }

    public function success(array $data = null, string $message = 'success'): JsonResponse
    {
        return new JsonResponse([
            'status' => 1,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function error(array $errorHttp = ErrorHttp::ERROR): JsonResponse
    {
        return new JsonResponse([
            'status' => 0,
            'message' => $errorHttp['message'] ?? 'error',
        ], $errorHttp['code'] ?? 500);
    }
}