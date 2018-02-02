<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Request;

use Assert\Assert;
use Loevgaard\Linkmobility\Response\BatchStatusResponse;
use Loevgaard\Linkmobility\ValueObject\Message;
use Loevgaard\Linkmobility\ValueObject\Recipient;
use Loevgaard\Linkmobility\ValueObject\Sender;

/**
 * @link https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017807/08.+Messages
 */
class PostMessageRequest extends Request
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
     * @var Recipient[]
     */
    protected $recipients;

    /**
     * @var Sender
     */
    protected $sender;

    /**
     * @var Message
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

    public function __construct(Sender $sender, Message $message, array $recipients)
    {
        $this->setSender($sender);
        $this->setMessage($message);
        $this->setRecipients($recipients);
    }

    /**
     * @inheritdoc
     */
    public function validate(): void
    {
        parent::validate();

        Assert::that($this->recipients)->isArray()->notEmpty();
        Assert::thatAll($this->recipients)->isInstanceOf(Recipient::class);

        // optional properties
        Assert::thatNullOr($this->status)->boolean();
        Assert::thatNullOr($this->statusUrl)->url();
        Assert::thatNullOr($this->returnData)->string()->notEmpty();
        Assert::thatNullOr($this->class)->integer()->choice(static::getClasses());
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
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_POST;
    }

    public function getUri(): string
    {
        return '/message.json';
    }

    public function getBody(): array
    {
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

    public function getResponseClass(): string
    {
        return BatchStatusResponse::class;
    }

    public function addRecipient(Recipient $recipient) : PostMessageRequest
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    /**
     * Returns the possible classes for the payload
     *
     * @return array
     */
    public static function getClasses() : array
    {
        return [
            self::CLASS_0 => self::CLASS_0,
            self::CLASS_1 => self::CLASS_1,
            self::CLASS_2 => self::CLASS_2,
            self::CLASS_3 => self::CLASS_3
        ];
    }

    /**
     * Returns the possible formats for the payload
     *
     * @return array
     */
    public static function getFormats() : array
    {
        return [
            self::FORMAT_GSM => self::FORMAT_GSM,
            self::FORMAT_UNICODE => self::FORMAT_UNICODE,
            self::FORMAT_BINARY => self::FORMAT_BINARY,
            self::FORMAT_WAPPUSH => self::FORMAT_WAPPUSH,
            self::FORMAT_MMS => self::FORMAT_MMS
        ];
    }

    /*
     * Getters / Setters
     */

    /**
     * @return Recipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param Recipient[] $recipients
     * @return PostMessageRequest
     */
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * @return Sender
     */
    public function getSender(): Sender
    {
        return $this->sender;
    }

    /**
     * @param Sender $sender
     * @return PostMessageRequest
     */
    public function setSender(Sender $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     * @return PostMessageRequest
     */
    public function setMessage(Message $message)
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
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
     * @return PostMessageRequest
     */
    public function setRevenueText(string $revenueText)
    {
        $this->revenueText = $revenueText;
        return $this;
    }
}
