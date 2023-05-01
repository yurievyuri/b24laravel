<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Handlers;
use Dev\Larabit\Hooks;
use Dev\Larabit\Http;
use Exception;

class Controller extends Http
{
    protected string $path = '/controller';
    protected Handlers $handler;

    /**
     * @throws Exception
     */
    public static function register(string $method = 'connection.register')
    {
        $data = Hooks::getInboundHookData();
        $obRes = (new self)
            ->setMethod($method)
            ->request($data);

        if (!$obRes->getResponse('success') ) {
            throw new Exception('Error: ' . $obRes->getResponse('message'));
        }

        return $obRes->getResponse();
    }

    public function setHandler(Handlers $handler): Controller
    {
        $this->handler = $handler;
        return $this;
    }

    public function send()
    {
        /*if ( $handler->useDump() ) {

        }*/
        $a= 1;
    }
}