<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use PHPUnit\Framework\TestCase;

class StringValueObjectTest extends TestCase
{
    public function testGetters()
    {
        $message = new Message('val');

        $this->assertSame('val', $message->get());
        $this->assertSame('val', (string)$message);
    }
}
