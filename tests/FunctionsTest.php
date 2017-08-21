<?php
namespace Loevgaard\Linkmobility;

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function testMessageLength()
    {
        $tests = [
            ['message' => 'Hello', 'unicode' => false, 'expected' => 5],
            ['message' => 'Hello', 'unicode' => true, 'expected' => 5],
            ['message' => 'Hello {}', 'unicode' => false, 'expected' => 10],
            ['message' => 'Hello {}', 'unicode' => true, 'expected' => 8],
            ['message' => "Hello \xF0\x9F\x98\x83", 'unicode' => true, 'expected' => 7],
        ];

        foreach ($tests as $test) {
            $count = messageLength($test['message'], $test['unicode']);
            $this->assertEquals($test['expected'], $count);
        }
    }
}
