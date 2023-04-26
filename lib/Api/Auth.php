<?php
namespace Dev\Larabit\Api;

use Dev\Larabit\Option;

class Auth extends \Dev\Larabit\Http
{
    protected $path = '/api/auth';

    public static function updateToken()
    {
        $res = (new self)->request([
            'email' => Option::getExternalUserEmail(),
            'password' => Option::getExternalUserPassword(),
        ]);
    }
}