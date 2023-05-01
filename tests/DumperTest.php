<?php

namespace Dev\Larabit\Tests;

use Dev\Larabit\Dumper;
use PHPUnit\Framework\TestCase;

class DumperTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testMakeAndTake()
    {
        $ar = [1,2,34,4,5,6,];
        $name = rand(12314, 24598273592387);
        $md5 = Dumper::make($name, $ar);
        $this->assertNotEmpty($md5);
        $res = Dumper::take($name, $md5);
        $this->assertSame($res, $ar);
    }
}
