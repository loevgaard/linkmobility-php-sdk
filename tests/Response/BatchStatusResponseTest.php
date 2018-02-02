<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response;

use PHPUnit\Framework\TestCase;

class BatchStatusResponseTest extends TestCase
{
    public function testValidResponse()
    {
        $data = [
            "stat" => [
                "sendtime" => "17-12-2014 08:42:24",
                "buffered" => 0,
                "received" => 1,
                "rejected" => 1
            ],
            "details" => [
                "sendtime" => "17-12-2014 08:42:24",
                "batchid" => "310701",
                "state" => "DONE"
            ],
            "status"=> 200
        ];

        $obj = json_decode(json_encode($data), true);

        $response = new BatchStatusResponse($obj);

        $this->assertNotNull($response->getStat());
        $this->assertInstanceOf(BatchStatusResponse\Stat::class, $response->getStat());
        $this->assertEquals(
            $data['stat']['sendtime'],
            $response->getStat()->getSendTime()->format('d-m-Y H:i:s')
        );
        $this->assertEquals($data['stat']['buffered'], $response->getStat()->getBuffered());
        $this->assertEquals($data['stat']['received'], $response->getStat()->getReceived());
        $this->assertEquals($data['stat']['rejected'], $response->getStat()->getRejected());

        $this->assertNotNull($response->getDetails());
        $this->assertInstanceOf(BatchStatusResponse\Details::class, $response->getDetails());
        $this->assertEquals($data['details']['sendtime'], $response->getDetails()->getSendTime()->format('d-m-Y H:i:s'));
        $this->assertEquals($data['details']['batchid'], $response->getDetails()->getBatchId());
        $this->assertEquals($data['details']['state'], $response->getDetails()->getState());

        $this->assertTrue($response->getDetails()->isDone());
        $this->assertFalse($response->getDetails()->isQueued());
        $this->assertFalse($response->getDetails()->isRunning());
    }
}
