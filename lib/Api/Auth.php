<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Option;

class Auth extends \Dev\Larabit\Http
{
    protected $path = '/api/auth';

    public static function register()
    {
        $res = (new self)
            ->setMethod(__FUNCTION__)
            ->request([
                'name' => Option::getExternalUserName(),
                'email' => Option::getExternalUserEmail(),
                'password' => Option::getExternalUserPassword(),
                'registration_token' => Option::getRegistrationToken()
            ])
            ->getResponse();

        if (isset($res['status']) && $res['status'] == true && !empty($res['token']))
        {
            Option::setExternalUserToken($res['token']);
        } else {
            Option::setExternalUserToken();
        }

        return $res;
    }

    public static function unregister()
    {

    }
}