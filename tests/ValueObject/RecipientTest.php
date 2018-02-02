<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * See the link below to see various valid recipients formats
 *
 * @link https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017807/08.+Messages#id-08.Messages-recipients
 */
class RecipientTest extends TestCase
{
    public function testConstructorListId()
    {
        new Recipient('4511223344');
        $this->assertTrue(true);
    }

    public function testConstructorContactId()
    {
        new Recipient('c1234');
        $this->assertTrue(true);
    }

    public function testConstructorPhoneNumber()
    {
        new Recipient('+4511223344');
        $this->assertTrue(true);
    }

    public function testException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Recipient('abc');
    }
}
