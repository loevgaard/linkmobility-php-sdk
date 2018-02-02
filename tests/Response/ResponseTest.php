<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetError()
    {
        $data = [
            'message' => 'error',
            'status' => 500
        ];

        $response = $this->getMockForAbstractClass(Response::class, [$data]);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('error', $response->getError());
    }
}
