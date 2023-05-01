<?php

namespace Dev\Larabit\Api;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function register()
    {
        $res = $this->request(__FUNCTION__);
        $this->assertTrue($res['success']);
    }

    /**
     * @test
     * @depends register
     * @return void
     */
    public function unregister()
    {
        $res = $this->request(__FUNCTION__);
        $this->assertTrue($res['success']);
    }

    private function request(string $method)
    {
        return (new Auth)
            ->setMethod($method)
            ->request($this->getParams())
            ->getResponse();
    }

    private function getParams(): array
    {
        return [
            'name' => 'testGen',
            'email' => 'testGen@genTest.co',
            'password' => 'testGenGenTest',
            'registration_token' => '2u82405vn032430987v5-2347v5165n1g1'
        ];
    }
}