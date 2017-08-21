<?php
namespace Loevgaard\Linkmobility\Webhook;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Loevgaard\Linkmobility\Payload\Message;
use PHPUnit\Framework\TestCase;

class DeliveryReportTest extends TestCase
{
    public function testValidRequest()
    {
        $request = new Request('GET', 'http://www.example.com/dlr.php?status=received&reason=
        &receivetime=1433242301&msgid=f5ffcafa047c107c8978c9318ddc5955&to=4512721272&statuscode=0
        &returndata=my-own-data&logdate=2015_06_02&mcc=238&mnc=1&batchid=6681153');
        $deliveryReport = new DeliveryReport($request);

        $this->assertEquals('received', $deliveryReport->getStatus());
        $this->assertEquals('', $deliveryReport->getReason());
        $this->assertInstanceOf(\DateTimeInterface::class, $deliveryReport->getReceiveTime());
        $this->assertEquals('2015-06-02', $deliveryReport->getReceiveTime()->format('Y-m-d'));
        $this->assertEquals('f5ffcafa047c107c8978c9318ddc5955', $deliveryReport->getMessageId());
        $this->assertEquals('4512721272', $deliveryReport->getTo());
        $this->assertEquals(0, $deliveryReport->getStatusCode());
        $this->assertEquals('my-own-data', $deliveryReport->getReturnData());
        $this->assertInstanceOf(\DateTimeInterface::class, $deliveryReport->getLogDate());
        $this->assertEquals('2015-06-02', $deliveryReport->getLogDate()->format('Y-m-d'));
        $this->assertEquals(238, $deliveryReport->getMcc());
        $this->assertEquals(1, $deliveryReport->getMnc());
        $this->assertEquals(6681153, $deliveryReport->getBatchId());
    }
}
