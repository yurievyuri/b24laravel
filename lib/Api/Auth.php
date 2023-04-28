<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Hooks;
use Dev\Larabit\Option;
use function PHPUnit\Framework\throwException;

class Auth extends \Dev\Larabit\Http
{
    protected $path = '/api/auth';

    public static function register(string $method)
    {
        $method = strtolower($method);
        $request = [
            'name' => Option::getExternalUserName(),
            'email' => Option::getExternalUserEmail(),
            'password' => Option::getExternalUserPassword(),
            'registration_token' => Option::getRegistrationToken(),
        ];
        /*if ( $method === 'register' ) {
            $request['webhook'] = Hooks::getInboundHookData();
        }*/
        $res = (new self)
            ->setMethod($method)
            ->request($request)
            ->getResponse();

        if (!isset($res['status']) || $res['status'] != true) {
            throw new \Exception('Error: ' . $res['message']);
        }

        if ($method === 'register' && !empty($res['token'])) {
            Option::setExternalUserToken($res['token']);
        } else {
            Option::setExternalUserToken(false);
        }

        return $res;
    }
}