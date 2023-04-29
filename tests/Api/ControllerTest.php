<?php

namespace Dev\Larabit\Api;

use Dev\Larabit\Hooks;
use Dev\Larabit\Option;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function register()
    {
        $res = $this->request('connection.register');
        $this->assertTrue($res['success']);
    }

    private function request(string $method)
    {
        return (new Controller)
            ->setMethod($method)
            ->request(Hooks::getInboundHookData())
            ->getResponse();
    }
}