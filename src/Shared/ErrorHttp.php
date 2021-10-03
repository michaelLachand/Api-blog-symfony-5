<?php


namespace App\Shared;


class ErrorHttp
{

    public const ERROR = ['message' => 'ERROR', 'code' => 500];
    public const FORM_INVALID = ['message' => 'FORM_INVALID', 'code' => 400];
    public const USERNAME_NOT_FOUND = ['message' => 'USERNAME_NOT_FOUND', 'code' => 404];
    public const USER_NOT_FOUND = ['message' => 'USER_NOT_FOUND', 'code' => 404];
    public const USERNAME_EXIST = ['message' => 'USERNAME_EXIST', 'code' => 400];
    public const PASSWORD_TOO_SHORT = ['message' => 'PASSWORD_TOO_SHORT', 'code' => 400];
    public const PASSWORD_INVALID = ['message' => 'PASSWORD_INVALID','code' => 400];
    public const PASSWORD_NOT_MATCH = ['message' => 'PASSWORD_NOT_MATCH','code' => 400];
    public const PAYS_NOT_FOUND = ['message' => 'PAYS_NOT_FOUND', 'code' => 404];
    public const PARAM_GET_NOT_FOUND = ['message' => 'PARAM_GET_NOT_FOUND', 'code' => 403];
    public const CATEGORIE_NOT_FOUND = ['message' => 'CATEGORIE_NOT_FOUND', 'code' => 404];
    public const ARTICLE_NOT_FOUND = ['message' => 'ARTICLE_NOT_FOUND', 'code' => 404];
    public const COMMENT_NOT_FOUND = ['message' => 'COMMENT_NOT_FOUND', 'code' => 404];
    public const ROLE_NOT_FOUND = ['message' => 'ROLE_NOT_FOUND', 'code' => 404];
    public const TOKEN_NOT_FOUND = ['message' => 'TOKEN_NOT_FOUND', 'code' => 400];
}