<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Hooks;

/**
 * @created on 29/04/2023 by yuriyuriev
 * updated.virtualbox
 * @soundtrack Pola & Bryson - Cold Love
 */
class Controller extends \Dev\Larabit\Http
{
    protected $path = '/controller';

    /**
     * @throws \Exception
     */
    public static function register(string $method = 'connection.register')
    {
        $data = Hooks::getInboundHookData();
        $obRes = (new self)
            ->setMethod($method)
            ->request($data);

        if (!$obRes->getResponse('success') ) {
            throw new \Exception('Error: ' . $obRes->getResponse('message'));
        }

        return $obRes->getResponse();
    }

}