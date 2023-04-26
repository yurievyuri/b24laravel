<?php

namespace Dev\Larabit;

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testGetExternalDomain()
    {
        $this->assertNotEmpty(Option::getExternalDomain());
    }

    public function testSetExternalUserToken()
    {
        $this->assertNotEmpty(Option::getExternalUserToken());
    }

    public function testGetExternalUserName()
    {
        $this->assertNotEmpty(Option::getExternalUserName());
    }

    public function testIsActive()
    {
        $this->assertTrue(Option::isActive());
    }

    public function testGetHttpProtocol()
    {
        $this->assertNotEmpty(Option::getHttpProtocol());
    }

    public function testGetExternalUserPassword()
    {
        $this->assertNotEmpty(Option::getExternalUserPassword());
    }

    public function testGetExternalUserEmail()
    {
        $this->assertNotEmpty(Option::getExternalUserEmail());
    }

    public function testGetExternalUserToken()
    {
        $this->assertNotEmpty(Option::getExternalUserToken());
    }

    public function testRegister()
    {
        $res = Option::register(        [
            'mid' => 'dev.larabit',
            'lang' => 'en',
            'autosave_id' => '2093cacaacaa5a348ada5037fe0168be8',
            'activate' => 'Y',
            'external_domain' => '192.168.0.68',
            'external_user_name' => 'larabit',
            'external_user_email' => 'larabit@mail.ru',
            'external_user_password' => 'rt11qw12',
            'registration_token' => '2u82405vn032430987v5-2347v5165n1g1',
            'http_protocol' => 'http',
            'disable_ssl_verification' => 'Y',
            'register' => 'register',
            'sessid' => '71e03af836ea2ca3d445aaf3f8702eee',
        ]);

        $this->assertTrue(true);
    }
}
