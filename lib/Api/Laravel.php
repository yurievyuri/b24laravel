<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Handlers;

class Laravel extends \Dev\Larabit\Http
{
    protected $path = '/api/controller/bitrix';

    public function __construct( Handlers $handler )
    {
        $this->hander = $handler;
    }

    public function send()
    {
        /*if ( $handler->useDump() ) {

        }*/
        $a= 1;
    }

}