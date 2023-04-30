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

    public function testGetExternalUserId()
    {
        $this->assertNotEmpty(Option::getExternalUserId());
        Option::setExternalUserId(null);
    }

    public function testUseAgent()
    {
        $this->assertNotEmpty(Option::getUseAgent());
    }
}
