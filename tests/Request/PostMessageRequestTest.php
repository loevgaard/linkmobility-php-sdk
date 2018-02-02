<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Payload;

use Loevgaard\Linkmobility\Request\PostMessageRequest;
use Loevgaard\Linkmobility\ValueObject\Message;
use Loevgaard\Linkmobility\ValueObject\Recipient;
use Loevgaard\Linkmobility\ValueObject\Sender;
use PHPUnit\Framework\TestCase;

class PostMessageRequestTest extends TestCase
{
    public function testGettersSetters()
    {
        $sender = new Sender('sender');
        $message = new Message('message');
        $recipients = [new Recipient('+4511223344')];
        $sendTime = new \DateTimeImmutable();
        $validity = new \DateInterval('P30D');
        $validityMinutes = 30 * 24 * 60;
        $pushExpire = new \DateTimeImmutable();

        $postMessageRequest = new PostMessageRequest($sender, $message, $recipients);
        $postMessageRequest
            ->setPrice(100)
            ->setStatus(true)
            ->setAdvanced('advanced')
            ->setAttachment(['attachment'])
            ->setCharity(true)
            ->setClass(PostMessageRequest::CLASS_1)
            ->setContentType(5)
            ->setFilter(['filter'])
            ->setStatusUrl('http://example.com')
            ->setReturnData('returndata')
            ->setSendTime($sendTime)
            ->setInvoiceText('invoice')
            ->setValidity($validity)
            ->setFormat(PostMessageRequest::FORMAT_UNICODE)
            ->setUdh('udh')
            ->setPushUrl('http://examplepush.com')
            ->setPushExpire($pushExpire)
            ->setSegmentation(['segmentation'])
            ->setPid(1010)
            ->setProtocol('protocol')
            ->setRevenueText('revenuetext')
        ;

        $this->assertSame($sender, $postMessageRequest->getSender());
        $this->assertSame($message, $postMessageRequest->getMessage());
        $this->assertSame(100, $postMessageRequest->getPrice());
        $this->assertTrue($postMessageRequest->isStatus());
        $this->assertSame('advanced', $postMessageRequest->getAdvanced());
        $this->assertSame(['attachment'], $postMessageRequest->getAttachment());
        $this->assertTrue($postMessageRequest->isCharity());
        $this->assertSame(PostMessageRequest::CLASS_1, $postMessageRequest->getClass());
        $this->assertSame(5, $postMessageRequest->getContentType());
        $this->assertSame(['filter'], $postMessageRequest->getFilter());
        $this->assertSame('http://example.com', $postMessageRequest->getStatusUrl());
        $this->assertSame('returndata', $postMessageRequest->getReturnData());
        $this->assertSame($sendTime, $postMessageRequest->getSendTime());
        $this->assertSame('invoice', $postMessageRequest->getInvoiceText());
        $this->assertSame($validityMinutes, $postMessageRequest->getValidity());
        $this->assertSame(PostMessageRequest::FORMAT_UNICODE, $postMessageRequest->getFormat());
        $this->assertSame('udh', $postMessageRequest->getUdh());
        $this->assertSame('http://examplepush.com', $postMessageRequest->getPushUrl());
        $this->assertSame((string)$pushExpire->getTimestamp(), $postMessageRequest->getPushExpire());
        $this->assertSame(['segmentation'], $postMessageRequest->getSegmentation());
        $this->assertSame(1010, $postMessageRequest->getPid());
        $this->assertSame('protocol', $postMessageRequest->getProtocol());
        $this->assertSame('revenuetext', $postMessageRequest->getRevenueText());

        $postMessageRequest->validate();
    }

    public function testAddRecipient()
    {
        $sender = new Sender('sender');
        $message = new Message('message');
        $recipients = [new Recipient('+4511223344')];

        $postMessageRequest = new PostMessageRequest($sender, $message, $recipients);
        $postMessageRequest->addRecipient(new Recipient('+4522334455'));

        $recipientsString = join(',', $postMessageRequest->getRecipients());

        $this->assertEquals('+4511223344,+4522334455', $recipientsString);
    }

    public function testSetMessageWithUnicode()
    {
        $sender = new Sender('sender');
        $message = new Message("Test \xF0\x9F\x98\x83");
        $recipients = [new Recipient('+4511223344')];

        $postMessageRequest = new PostMessageRequest($sender, $message, $recipients);

        $this->assertSame(PostMessageRequest::FORMAT_UNICODE, $postMessageRequest->getFormat());
    }

    /*
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


    */

    /**
     * @return Message
     */
    /*
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
    */
}
