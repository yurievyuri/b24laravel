<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Option;
use Exception;

class Auth extends Controller
{
    protected string $path = '/auth';

    /**
     * @throws Exception
     */
    public static function register(string $method = 'register')
    {
        $method = strtolower($method);
        $request = [
            'name' => Option::getExternalUserName(),
            'email' => Option::getExternalUserEmail(),
            'password' => Option::getExternalUserPassword(),
            'registration_token' => Option::getRegistrationToken()
        ];
        $obRes = (new self)
            ->setMethod($method)
            ->request($request);

        if (!$obRes->getResponse('success') ) {
            throw new Exception('Error: ' . $obRes->getResponse('message'));
        }
        if ($method === 'register' && $obRes->getData('token') ) {
            Option::setExternalUserToken($obRes->getData('token'));
        } else {
            Option::setExternalUserToken(false);
        }

        if ( $obRes->getData('user_id') ){
            Option::setExternalUserId((int)$obRes->getData('user_id'));
        } else {
            Option::setExternalUserId(0);
        }

        return $obRes->getResponse();
    }
}