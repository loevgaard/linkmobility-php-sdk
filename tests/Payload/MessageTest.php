<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Payload;

use Loevgaard\Linkmobility\Exception\InvalidPayloadException;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /*
    public function testGettersSetters()
    {
        $message = new Message();

        $val = 'message';
        $message->setMessage($val);
        $this->assertEquals($val, $message->getMessage());

        $val = 'sender';
        $message->setSender($val);
        $this->assertEquals($val, $message->getSender());

        $val = ['+4511223344'];
        $message->setRecipients($val);
        $this->assertEquals($val, $message->getRecipients());

        $val = 100;
        $message->setPrice($val);
        $this->assertEquals($val, $message->getPrice());

        $val = true;
        $message->setStatus($val);
        $this->assertEquals($val, $message->isStatus());

        $val = 'advanced';
        $message->setAdvanced($val);
        $this->assertEquals($val, $message->getAdvanced());

        $val = ['attachment'];
        $message->setAttachment($val);
        $this->assertEquals($val, $message->getAttachment());

        $val = true;
        $message->setCharity($val);
        $this->assertEquals($val, $message->isCharity());

        $val = Message::CLASS_1;
        $message->setClass($val);
        $this->assertEquals($val, $message->getClass());

        $val = 1;
        $message->setContentType($val);
        $this->assertEquals($val, $message->getContentType());

        $val = ['filter'];
        $message->setFilter($val);
        $this->assertEquals($val, $message->getFilter());

        $val = 'status url';
        $message->setStatusUrl($val);
        $this->assertEquals($val, $message->getStatusUrl());

        $val = 'return data';
        $message->setReturnData($val);
        $this->assertEquals($val, $message->getReturnData());

        $val = new \DateTimeImmutable();
        $message->setSendTime($val);
        $this->assertEquals($val, $message->getSendTime());

        $val = 'invoice text';
        $message->setInvoiceText($val);
        $this->assertEquals($val, $message->getInvoiceText());

        $val = 0;
        $message->setValidity($val);
        $this->assertEquals($val, $message->getValidity());

        $val = 'format';
        $message->setFormat($val);
        $this->assertEquals($val, $message->getFormat());

        $val = 'udh';
        $message->setUdh($val);
        $this->assertEquals($val, $message->getUdh());

        $val = 'push url';
        $message->setPushUrl($val);
        $this->assertEquals($val, $message->getPushUrl());

        $val = 'push expire';
        $message->setPushExpire($val);
        $this->assertEquals($val, $message->getPushExpire());

        $val = ['segmentation'];
        $message->setSegmentation($val);
        $this->assertEquals($val, $message->getSegmentation());

        $val = 10;
        $message->setPid($val);
        $this->assertEquals($val, $message->getPid());

        $val = 'protocol';
        $message->setProtocol($val);
        $this->assertEquals($val, $message->getProtocol());

        $val = 'revenue text';
        $message->setRevenueText($val);
        $this->assertEquals($val, $message->getRevenueText());
    }

    public function testGetPayload()
    {
        $sendTimeObject = new \DateTimeImmutable();

        $payload = [
            'recipients' => '+4511223344',
            'sender' => 'sender',
            'message' => 'message',
            'status' => true,
            'statusurl' => 'https://www.example.com/statusurl',
            'returndata' => 'returndata',
            'class' => Message::CLASS_1,
            'sendtime' => $sendTimeObject->format('d-m-Y H:i'),
            'price' => 100,
            'charity' => false,
            'invoicetext' => 'invoicetext',
            'validity' => 60,
            'contenttype' => 1,
            'format' => Message::FORMAT_GSM,
            'udh' => 'udh',
            'attachment' => ['attachment'],
            'pushurl' => 'https://www.example.com/pushurl',
            'pushexpire' => 'pushexpire',
            'filter' => ['filter'],
            'segmentation' => ['segmentation'],
            'pid' => 1,
            'advanced' => 'advanced',
            'protocol' => 'protocol',
            'revenuetext' => 'revenuetext'
        ];

        $message = new Message();
        $message->addRecipient($payload['recipients'])
            ->setSender($payload['sender'])
            ->setMessage($payload['message'])
            ->setStatus($payload['status'])
            ->setStatusUrl($payload['statusurl'])
            ->setReturnData($payload['returndata'])
            ->setClass($payload['class'])
            ->setSendTime($sendTimeObject)
            ->setPrice($payload['price'])
            ->setCharity($payload['charity'])
            ->setInvoiceText($payload['invoicetext'])
            ->setValidity($payload['validity'])
            ->setContentType($payload['contenttype'])
            ->setFormat($payload['format'])
            ->setUdh($payload['udh'])
            ->setAttachment($payload['attachment'])
            ->setPushUrl($payload['pushurl'])
            ->setPushExpire($payload['pushexpire'])
            ->setFilter($payload['filter'])
            ->setSegmentation($payload['segmentation'])
            ->setPid($payload['pid'])
            ->setAdvanced($payload['advanced'])
            ->setProtocol($payload['protocol'])
            ->setRevenueText($payload['revenuetext'])
        ;

        $payload = ['message' => $payload];

        $this->assertEquals($payload, $message->getPayload());

        // test invalid payload
        $message->setMessage('');
        $this->expectException(InvalidPayloadException::class);
        $message->getPayload();
    }

    public function testValidMessage()
    {
        $message = $this->getValidMessage();
        $message->validate();
        $this->assertTrue(true);
    }

    public function testEmptyMessage()
    {
        $message = $this->getValidMessage();

        $message->setMessage('');
        $this->expectException(InvalidPayloadException::class);
        $message->validate();
        $this->assertTrue(true);
    }

    public function testEmptySender()
    {
        $message = $this->getValidMessage();

        $message->setSender('');
        $this->expectException(InvalidPayloadException::class);
        $message->validate();
        $this->assertTrue(true);
    }

    public function testLongSender()
    {
        $message = $this->getValidMessage();

        $message->setSender('very long sender id');
        $this->expectException(InvalidPayloadException::class);
        $message->validate();
        $this->assertTrue(true);
    }

    public function testLongNumericSender()
    {
        $message = $this->getValidMessage();

        $message->setSender('+45112233445566778899');
        $message->validate();
        $this->assertTrue(true);
    }

    public function testWrongLongNumericSender()
    {
        $message = $this->getValidMessage();

        $message->setSender('45112233445566778899');
        $this->expectException(InvalidPayloadException::class);
        $message->validate();
        $this->assertTrue(true);
    }

    public function testValidRecipients()
    {
        $message = $this->getValidMessage();

        $message->setRecipients([
            'c123', '+4511223344', '123456'
        ]);
        $message->validate();

        $this->assertTrue(true);
    }

    public function testAddRecipient()
    {
        $message = new Message();
        $message->addRecipient('+4511223344');

        $this->assertEquals([
            '+4511223344'
        ], $message->getRecipients());
    }
    */

    /**
     * @return Message
     */
    private function getValidMessage()
    {
        $message = new Message();
        $message
            ->setMessage('message')
            ->setSender('sender')
            ->setRecipients([
                '+4511223344'
            ])
        ;

        return $message;
    }
}
