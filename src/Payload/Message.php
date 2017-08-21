<?php
namespace Loevgaard\Linkmobility\Payload;

use Assert\Assert;
use Loevgaard\Linkmobility;
use Loevgaard\Linkmobility\Exception\InvalidPayloadException;
use Assert\AssertionFailedException;

/**
 * Class Message
 * @package Loevgaard\Linkmobility\Payload
 * @see https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017807/08.+Messages
 */
class Message implements PayloadInterface
{
    /*
     * Show message directly on phone. The message is not saved on the phone. (Also known as flash messages)
     */
    const CLASS_0 = 0;

    /*
     * Save message in phone memory. Either on the phone or in SIM.
     */
    const CLASS_1 = 1;

    /*
     * Message contains SIM data.
     */
    const CLASS_2 = 2;

    /*
     * Message contains info that indicate that it should be
     * sent to external units, normally used by terminal equipment.
     */
    const CLASS_3 = 3;

    /*
     * Send normal message (160 chars, but if more than 160 chars, 153 chars per part message)
     */
    const FORMAT_GSM = 'GSM';

    /*
     * To send speciality chars like chinese letters. A normal message is 160 chars, but ifyou use
     * unicode each message can only hold 70 chars (But if more than 70 chars, 67 chars per part message)
     */
    const FORMAT_UNICODE = 'UNICODE';

    /*
     * Send a binary message body in hex and define udh
     */
    const FORMAT_BINARY = 'BINARY';

    /*
     * Send a link that is opened on the phone
     */
    const FORMAT_WAPPUSH = 'WAPPUSH';

    /*
     * Array of attachments to send as MMS To send a presentation, the first attachment
     * needs to be a SMIL document with the extension .smil Sender should be a valid shortcode
     */
    const FORMAT_MMS = 'MMS';

    /**
     * @var array
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var boolean
     */
    protected $status;

    /**
     * @var string
     */
    protected $statusUrl;

    /**
     * @var string
     */
    protected $returnData;

    /**
     * @var int
     */
    protected $class;

    /**
     * @var \DateTimeInterface
     */
    protected $sendTime;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var boolean
     */
    protected $charity;

    /**
     * @var string
     */
    protected $invoiceText;

    /**
     * @var int
     */
    protected $validity;

    /**
     * @var integer
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $udh;

    /**
     * @var array
     */
    protected $attachment;

    /**
     * @var string
     */
    protected $pushUrl;

    /**
     * @var string
     */
    protected $pushExpire;

    /**
     * @var array
     */
    protected $filter;

    /**
     * @var array
     */
    protected $segmentation;

    /**
     * @var int
     */
    protected $pid;

    /**
     * @var string
     */
    protected $advanced;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $revenueText;

    public function __construct()
    {
        $this->recipients = [];
    }

    public static function create(string $sender, string $message, array $recipients)
    {
        $obj = new Message();
        $obj->setSender($sender)
            ->setMessage($message)
            ->setRecipients($recipients)
        ;

        return $message;
    }

    /**
     * @inheritdoc
     */
    public function getPayload(): array
    {
        $this->validate();

        $payload = [
            'recipients' => join(',', $this->recipients),
            'sender' => $this->sender,
            'message' => $this->message,
            'status' => $this->status,
            'statusurl' => $this->statusUrl,
            'returndata' => $this->returnData,
            'class' => $this->class,
            'sendtime' => $this->sendTime ? $this->sendTime->format('d-m-Y H:i') : null,
            'price' => $this->price,
            'charity' => $this->charity,
            'invoicetext' => $this->invoiceText,
            'validity' => $this->validity,
            'contenttype' => $this->contentType,
            'format' => $this->format,
            'udh' => $this->udh,
            'attachment' => $this->attachment,
            'pushurl' => $this->pushUrl,
            'pushexpire' => $this->pushExpire,
            'filter' => $this->filter,
            'segmentation' => $this->segmentation,
            'pid' => $this->pid,
            'advanced' => $this->advanced,
            'protocol' => $this->protocol,
            'revenuetext' => $this->revenueText
        ];

        $payload =  array_filter($payload, function ($elm) {
            return !is_null($elm);
        });

        // we wrap the payload in a message array according to
        // https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017829/Sending+SMS
        return [
            'message' => $payload
        ];
    }

    /**
     * @inheritdoc
     */
    public function validate(): void
    {
        $recipientPattern = '/^(\+|c)?[0-9]+$/i';

        try {
            // required properties
            Assert::that($this->recipients)->isArray()->notEmpty();
            Assert::thatAll($this->recipients)->notEmpty()->regex($recipientPattern);
            Assert::that($this->sender)->string()->notEmpty();

            // if the sender is alphanumeric, test the length
            if (!preg_match('/^\+[0-9]+$/i', $this->sender)) {
                Assert::that($this->sender)->maxLength(11);
            }
            Assert::that($this->message)->string()->notEmpty();

            // optional properties
            Assert::thatNullOr($this->status)->boolean();
            Assert::thatNullOr($this->statusUrl)->url();
            Assert::thatNullOr($this->returnData)->string()->notEmpty();
            Assert::thatNullOr($this->class)->integer()->inArray(static::getClasses());
            Assert::thatNullOr($this->sendTime)->isInstanceOf(\DateTimeInterface::class);
            Assert::thatNullOr($this->price)->integer()->greaterOrEqualThan(100);
            Assert::thatNullOr($this->charity)->boolean();
            Assert::thatNullOr($this->invoiceText)->string()->notEmpty();
            Assert::thatNullOr($this->validity)->integer();
            Assert::thatNullOr($this->contentType)->integer();
            Assert::thatNullOr($this->format)->string()->inArray(static::getFormats());
            Assert::thatNullOr($this->udh)->string()->notEmpty();
            Assert::thatNullOr($this->attachment)->isArray()->notEmpty();
            Assert::thatNullOr($this->pushUrl)->url();
            Assert::thatNullOr($this->pushExpire)->string()->notEmpty();
            Assert::thatNullOr($this->filter)->isArray()->notEmpty();
            Assert::thatNullOr($this->segmentation)->isArray()->notEmpty();
            Assert::thatNullOr($this->pid)->integer();
            Assert::thatNullOr($this->advanced)->string()->notEmpty();
            Assert::thatNullOr($this->protocol)->string()->notEmpty();
            Assert::thatNullOr($this->revenueText)->string()->notEmpty();
        } catch (AssertionFailedException $e) {
            throw new InvalidPayloadException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function addRecipient($recipient) : Message
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function getChunkCount() : int
    {
        return Linkmobility\chunkCount($this->message);
    }

    /**
     * Returns the possible classes for the payload
     *
     * @return array
     */
    public static function getClasses() : array
    {
        return [static::CLASS_0, static::CLASS_1, static::CLASS_2, static::CLASS_3];
    }

    /**
     * Returns the possible formats for the payload
     *
     * @return array
     */
    public static function getFormats() : array
    {
        return [
            static::FORMAT_GSM,
            static::FORMAT_UNICODE,
            static::FORMAT_BINARY,
            static::FORMAT_WAPPUSH,
            static::FORMAT_MMS
        ];
    }

    /*
     * Getters / Setters
     */

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     * @return Message
     */
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     * @return Message
     */
    public function setSender(string $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Message
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusUrl(): string
    {
        return $this->statusUrl;
    }

    /**
     * @param string $statusUrl
     * @return Message
     */
    public function setStatusUrl(string $statusUrl)
    {
        $this->statusUrl = $statusUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnData(): string
    {
        return $this->returnData;
    }

    /**
     * @param string $returnData
     * @return Message
     */
    public function setReturnData(string $returnData)
    {
        $this->returnData = $returnData;
        return $this;
    }

    /**
     * @return int
     */
    public function getClass(): int
    {
        return $this->class;
    }

    /**
     * @param int $class
     * @return Message
     */
    public function setClass(int $class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSendTime(): \DateTimeInterface
    {
        return $this->sendTime;
    }

    /**
     * @param \DateTimeInterface $sendTime
     * @return Message
     */
    public function setSendTime(\DateTimeInterface $sendTime)
    {
        $this->sendTime = $sendTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return Message
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCharity(): bool
    {
        return $this->charity;
    }

    /**
     * @param bool $charity
     * @return Message
     */
    public function setCharity(bool $charity)
    {
        $this->charity = $charity;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceText(): string
    {
        return $this->invoiceText;
    }

    /**
     * @param string $invoiceText
     * @return Message
     */
    public function setInvoiceText(string $invoiceText)
    {
        $this->invoiceText = $invoiceText;
        return $this;
    }

    /**
     * @return int
     */
    public function getValidity(): int
    {
        return $this->validity;
    }

    /**
     * @param int $validity
     * @return Message
     */
    public function setValidity(int $validity)
    {
        $this->validity = $validity;
        return $this;
    }

    /**
     * @return int
     */
    public function getContentType(): int
    {
        return $this->contentType;
    }

    /**
     * @param int $contentType
     * @return Message
     */
    public function setContentType(int $contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return Message
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getUdh(): string
    {
        return $this->udh;
    }

    /**
     * @param string $udh
     * @return Message
     */
    public function setUdh(string $udh)
    {
        $this->udh = $udh;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachment(): array
    {
        return $this->attachment;
    }

    /**
     * @param array $attachment
     * @return Message
     */
    public function setAttachment(array $attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * @return string
     */
    public function getPushUrl(): string
    {
        return $this->pushUrl;
    }

    /**
     * @param string $pushUrl
     * @return Message
     */
    public function setPushUrl(string $pushUrl)
    {
        $this->pushUrl = $pushUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPushExpire(): string
    {
        return $this->pushExpire;
    }

    /**
     * @param string $pushExpire
     * @return Message
     */
    public function setPushExpire(string $pushExpire)
    {
        $this->pushExpire = $pushExpire;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     * @return Message
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return array
     */
    public function getSegmentation(): array
    {
        return $this->segmentation;
    }

    /**
     * @param array $segmentation
     * @return Message
     */
    public function setSegmentation(array $segmentation)
    {
        $this->segmentation = $segmentation;
        return $this;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     * @return Message
     */
    public function setPid(int $pid)
    {
        $this->pid = $pid;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvanced(): string
    {
        return $this->advanced;
    }

    /**
     * @param string $advanced
     * @return Message
     */
    public function setAdvanced(string $advanced)
    {
        $this->advanced = $advanced;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return Message
     */
    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevenueText(): string
    {
        return $this->revenueText;
    }

    /**
     * @param string $revenueText
     * @return Message
     */
    public function setRevenueText(string $revenueText)
    {
        $this->revenueText = $revenueText;
        return $this;
    }
}
