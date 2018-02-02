<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Webhook;

use GuzzleHttp\Psr7\Request;
use Loevgaard\Linkmobility\Exception\InvalidDateTimeFormatException;
use PHPUnit\Framework\TestCase;

class DeliveryReportTest extends TestCase
{
    public function testValidRequest()
    {
        $request = new Request('GET', 'http://www.example.com/dlr.php?status=received&reason='.
        '&receivetime=1433242301&msgid=f5ffcafa047c107c8978c9318ddc5955&to=4512721272&statuscode=0'.
        '&returndata=my-own-data&logdate=2015_06_02&mcc=238&mnc=1&batchid=6681153&push_price=100');

        $deliveryReport = new DeliveryReport($request);

        $this->assertSame('received', $deliveryReport->getStatus());
        $this->assertSame('', $deliveryReport->getReason());
        $this->assertInstanceOf(\DateTimeInterface::class, $deliveryReport->getReceiveTime());
        $this->assertSame('2015-06-02', $deliveryReport->getReceiveTime()->format('Y-m-d'));
        $this->assertSame('f5ffcafa047c107c8978c9318ddc5955', $deliveryReport->getMessageId());
        $this->assertSame('4512721272', $deliveryReport->getTo());
        $this->assertSame(0, $deliveryReport->getStatusCode());
        $this->assertSame('my-own-data', $deliveryReport->getReturnData());
        $this->assertInstanceOf(\DateTimeInterface::class, $deliveryReport->getLogDate());
        $this->assertSame('2015-06-02', $deliveryReport->getLogDate()->format('Y-m-d'));
        $this->assertSame(238, $deliveryReport->getMcc());
        $this->assertSame(1, $deliveryReport->getMnc());
        $this->assertSame(6681153, $deliveryReport->getBatchId());
        $this->assertTrue($deliveryReport->isReceived());
        $this->assertFalse($deliveryReport->isRejected());
        $this->assertFalse($deliveryReport->isBuffered());
        $this->assertFalse($deliveryReport->isExpired());
        $this->assertSame(100, $deliveryReport->getPushPrice());
        $this->assertSame($request, $deliveryReport->getRequest());
    }

    public function testWrongFormatLogDate()
    {
        $this->expectException(InvalidDateTimeFormatException::class);
        $request = new Request('GET', 'http://www.example.com/dlr.php?status=received&reason='.
            '&receivetime=1433242301&msgid=f5ffcafa047c107c8978c9318ddc5955&to=4512721272&statuscode=0'.
            '&returndata=my-own-data&logdate=2015-06-02&mcc=238&mnc=1&batchid=6681153&push_price=100');

        new DeliveryReport($request);
    }

    public function testWrongFormatReceiveTime()
    {
        $this->expectException(InvalidDateTimeFormatException::class);
        $request = new Request('GET', 'http://www.example.com/dlr.php?status=received&reason='.
            '&receivetime=1433s212312342301&msgid=f5ffcafa047c107c8978c9318ddc5955&to=4512721272&statuscode=0'.
            '&returndata=my-own-data&logdate=2015_06_02&mcc=238&mnc=1&batchid=6681153&push_price=100');

        new DeliveryReport($request);
    }
}
