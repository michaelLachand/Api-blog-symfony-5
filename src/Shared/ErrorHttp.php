<?php


namespace App\Shared;


class ErrorHttp
{
     public const ERROR = ['message' => 'ERROR', 'code' => 500];
     public const FORM_INVALID = ['message' => 'FORM_INVALID', 'code' => 400];
     public const USERNAME_NOT_FOUND = ['message' => 'USERNAME_NOT_FOUND', 'code' => 404];
     public const USERNAME_EXIST = ['message' => 'USERNAME_EXIST', 'code' => 400];
     public const PASSWORD_TOO_SHORT = ['message' => 'PASSWORD_TOO_SHORT', 'code' => 400];
     public const PASSWORD_INVALID = ['message' => 'PASSWORD_INVALID','code' => 400];
     public const PAYS_NOT_FOUND = ['message' => 'PAYS_NOT_FOUND', 'code' => 404];

}