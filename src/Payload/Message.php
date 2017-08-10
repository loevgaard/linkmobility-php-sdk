<?php
namespace Loevgaard\Linkmobility\Payload;

use Assert\Assert;
use Loevgaard\Linkmobility\Exception\InvalidPayloadException;

/**
 * Class Message
 * @package Loevgaard\Linkmobility\Payload
 * @see https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017807/08.+Messages
 */
class Message implements PayloadInterface
{
    const CLASS_0 = 0; // Show message directly on phone. The message is not saved on the phone. (Also known as flash messages)
    const CLASS_1 = 1; // Save message in phone memory. Either on the phone or in SIM.
    const CLASS_2 = 2; // Message contains SIM data.
    const CLASS_3 = 3; // Message contains info that indicate that it should be sent to external units, normally used by terminal equipment.

    /**
     * Note! These content types currently only apply to danish shortcodes
     * See https://linkmobility.atlassian.net/wiki/display/COOL/14.+Contenttypes for more info
     */
    const CONTENT_TYPE_1 = 1; // Ringetoner og billeder
    const CONTENT_TYPE_2 = 2; // Videoklip og tv
    const CONTENT_TYPE_3 = 3; // Voksenindhold
    const CONTENT_TYPE_4 = 4; // Musik
    const CONTENT_TYPE_5 = 5; // Lydbøger og podcasts
    const CONTENT_TYPE_6 = 6; // Mobilspil
    const CONTENT_TYPE_7 = 7; // Chat tjenester
    const CONTENT_TYPE_8 = 8; // Konkurrence og afstemning
    const CONTENT_TYPE_9 = 9; // M-payment (Fysik varer)
    const CONTENT_TYPE_10 = 10; // Nyheder og information
    const CONTENT_TYPE_11 = 11; // Indsamlinger / donationer (Humanitære organisationer)
    const CONTENT_TYPE_12 = 12; // Telemetri (M2M)
    const CONTENT_TYPE_13 = 13; // Diverse
    const CONTENT_TYPE_14 = 14; // Indsamlinger / donationer (ikke humanitære organisationer)
    const CONTENT_TYPE_15 = 15; // Lotteri (moms fri)

    const FORMAT_GSM = 'GSM'; // Send normal message (160 chars, but if more than 160 chars, 153 chars per part message)
    const FORMAT_UNICODE = 'UNICODE'; // To send speciality chars like chinese letters. A normal message is 160 chars, but if you use unicode each message can only hold 70 chars (But if more than 70 chars, 67 chars per part message)
    const FORMAT_BINARY = 'BINARY'; // Send a binary message body in hex and define udh
    const FORMAT_WAPPUSH = 'WAPPUSH'; // Send a link that is opened on the phone
    const FORMAT_MMS = 'MMS'; // Array of attachments to send as MMS To send a presentation, the first attachment needs to be a SMIL document with the extension .smil Sender should be a valid shortcode

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
     * @var string
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
     * @var string
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
     * @var string
     */
    protected $filter;

    /**
     * @var string
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

    /**
     * @inheritdoc
     */
    public function getPayload(): array
    {
        if(!$this->validate()) {
            throw new InvalidPayloadException('The payload is invalid');
        }
        $payload = [
            'recipients' => $this->recipients,
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

        return array_filter($payload);
    }

    /**
     * @inheritdoc
     */
    public function validate(): bool
    {
        // required properties
        Assert::that($this->recipients)->isArray()->notEmpty();
        Assert::that($this->sender)->string();
        Assert::that($this->message)->string();

        // optional properties
        Assert::thatNullOr($this->status)->boolean();
        Assert::thatNullOr($this->statusUrl)->url();
        Assert::thatNullOr($this->returnData)->string();
        Assert::thatNullOr($this->class)->integer()->inArray(static::getClasses());
        Assert::thatNullOr($this->sendTime)->isInstanceOf(\DateTimeInterface::class);
        Assert::thatNullOr($this->price)->integer()->greaterOrEqualThan(100);
        Assert::thatNullOr($this->charity)->boolean();
        Assert::thatNullOr($this->invoiceText)->string();
        Assert::thatNullOr($this->validity)->integer();
        Assert::thatNullOr($this->contentType)->integer()->inArray(static::getContentTypes());
        Assert::thatNullOr($this->format)->string()->inArray(static::getFormats());
        Assert::thatNullOr($this->udh)->string();
        Assert::thatNullOr($this->attachment)->isArray()->notEmpty();
        Assert::thatNullOr($this->pushUrl)->url();
        Assert::thatNullOr($this->pushExpire)->string();
        Assert::thatNullOr($this->filter)->isArray()->notEmpty();
        Assert::thatNullOr($this->segmentation)->isArray()->notEmpty();
        Assert::thatNullOr($this->pid)->integer();
        Assert::thatNullOr($this->advanced)->string();
        Assert::thatNullOr($this->protocol)->string();
        Assert::thatNullOr($this->revenueText)->string();

        return true;
    }

    public function addRecipient($recipient) : Message
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
        return [static::CLASS_0, static::CLASS_1, static::CLASS_2, static::CLASS_3];
    }

    /**
     * Returns the possible content types for the payload
     *
     * @return array
     */
    public static function getContentTypes() : array
    {
        return [
            static::CONTENT_TYPE_1,
            static::CONTENT_TYPE_2,
            static::CONTENT_TYPE_3,
            static::CONTENT_TYPE_4,
            static::CONTENT_TYPE_5,
            static::CONTENT_TYPE_6,
            static::CONTENT_TYPE_7,
            static::CONTENT_TYPE_8,
            static::CONTENT_TYPE_9,
            static::CONTENT_TYPE_10,
            static::CONTENT_TYPE_11,
            static::CONTENT_TYPE_12,
            static::CONTENT_TYPE_13,
            static::CONTENT_TYPE_14,
            static::CONTENT_TYPE_15,
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
            static::FORMAT_GSM,
            static::FORMAT_UNICODE,
            static::FORMAT_BINARY,
            static::FORMAT_WAPPUSH,
            static::FORMAT_MMS
        ];
    }


}
