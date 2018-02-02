<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testLength()
    {
        $tests = [
            ['message' => 'Hello', 'expected' => 5], // tests normal behavior
            ['message' => 'Hello |^€{}[]~\\', 'expected' => 24], // tests doubles
            ['message' => "Hello \xF0\x9F\x98\x83", 'expected' => 7], // tests non-gsm7 characters
        ];

        foreach ($tests as $test) {
            $message = new Message($test['message']);
            $this->assertEquals($test['expected'], $message->length());
        }
    }

    public function testChunkCount()
    {
        $tests = [
            ['message' => 'Hello', 'expected' => 1],
            ['message' => 'Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello HelloHello', 'expected' => 1], // 160 characters
            ['message' => 'Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello', 'expected' => 2], // 161 characters
            ['message' => "Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello HelHello \xF0\x9F\x98\x83", 'expected' => 1], // utf 8 with 70 characters
            ['message' => "Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hel Hello \xF0\x9F\x98\x83", 'expected' => 2], // utf 8 with 71 characters
        ];

        foreach ($tests as $test) {
            $message = new Message($test['message']);
            $this->assertEquals($test['expected'], $message->chunkCount());
        }
    }
}
