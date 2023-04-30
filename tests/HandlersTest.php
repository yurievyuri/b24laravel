<?php


use Dev\Larabit\Handlers;
use PHPUnit\Framework\TestCase;

class HandlersTest extends TestCase
{
    public function test__callStaticActionBefore()
    {
        $res = \Dev\Larabit\Handlers::ActionOnBeforeCrmSomethingData([
            'data1' => 1,
            'data2' => 2
        ], [
            'data3' => 3,
            'data4' => 4
        ]);

        $this->assertTrue(true);
    }

    public function test__callStaticActionAfter()
    {
        $res = \Dev\Larabit\Handlers::ActionOnAfterCrmSomethingData([
            'data1' => 1,
            'data2' => 2
        ], [
            'data3' => 3,
            'data4' => 4
        ]);

        $this->assertTrue(true);
    }

    public function test__callStaticReaction()
    {
        $res = \Dev\Larabit\Handlers::ReactionOnBeforeCrmSomethingData([
            'data1' => 1,
            'data2' => 2
        ], [
            'data3' => 3,
            'data4' => 4
        ]);

        $this->assertTrue(true);
    }
}
