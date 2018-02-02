<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Payload;

use Loevgaard\Linkmobility\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetters()
    {
        $request = $this->getMockForAbstractClass(Request::class);

        $this->assertSame([], $request->getBody());
        $this->assertSame([], $request->getOptions());
    }
}
