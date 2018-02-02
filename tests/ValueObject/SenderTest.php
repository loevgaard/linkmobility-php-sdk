<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testConstructorNumeric()
    {
        new Sender('+4511223344');
        $this->assertTrue(true);
    }

    public function testConstructorAlphaNumeric1()
    {
        new Sender('Sender');
        $this->assertTrue(true);
    }

    public function testConstructorAlphaNumeric2()
    {
        $this->expectException(InvalidArgumentException::class);
        $sender = new Sender('SenderSender');
    }
}
