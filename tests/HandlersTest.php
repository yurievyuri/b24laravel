<?php
namespace Dev\Larabit\Tests;
use Dev\Larabit\Handlers;
use PHPUnit\Framework\TestCase;

class HandlersTest extends TestCase
{
    public function test__callStaticActionBefore()
    {
        $res = Handlers::ActionOnBeforeCrmSomethingData($this->getArray());

        $this->assertTrue(true);
    }

    public function test__callStaticActionAfter()
    {
        $res = Handlers::ActionOnAfterCrmSomethingData($this->getArray());

        $this->assertTrue(true);
    }

    public function test__callStaticReaction()
    {
        $res = Handlers::ReactionOnBeforeCrmSomethingData($this->getArray());

        $this->assertTrue(true);
    }


    private function getArray(): array
    {
        return [
            'ID' => 23525,
            'DATA' => 12312312
        ];
    }
}
