<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Webhook;

use Loevgaard\Linkmobility\Exception\InvalidWebhookException;
use Psr\Http\Message\RequestInterface;

class DeliveryReport
{
    /**
     * The message is received
     */
    const STATUS_RECEIVED = 'received';

    /**
     * The message is rejected by the SMSC. (Please see the list of status codes for an explanation)
     */
    const STATUS_REJECTED = 'rejected';

    /**
     * The message was not delivered and will try to get sent again at another time
     */
    const STATUS_BUFFERED = 'buffered';

    /**
     * The validity period has expired, the message wasn't delivered
     */
    const STATUS_EXPIRED = 'expired';

    /**
     * The HTTP request made by Linkmobility
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @var \DateTimeImmutable
     */
    protected $receiveTime;

    /**
     * @var string
     */
    protected $messageId;

    /**
     * @var string
     */
    protected $to;

    /**
     * @see https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017824/Delivery+report
     *
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $returnData;

    /**
     * @var \DateTimeImmutable
     */
    protected $logDate;

    /**
     * @var int
     */
    protected $mcc;

    /**
     * @var int
     */
    protected $mnc;

    /**
     * @var int
     */
    protected $batchId;

    /**
     * @var int
     */
    protected $pushPrice;

    /**
     * DeliveryReport constructor.
     * @param RequestInterface $request
     * @throws InvalidWebhookException
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;

        parse_str($this->request->getUri()->getQuery(), $query);

        // strings are just assigned if set
        $this->status = $query['status'] ?? null;
        $this->reason = $query['reason'] ?? null;
        $this->messageId = $query['msgid'] ?? null;
        $this->to = $query['to'] ?? null;
        $this->returnData = $query['returndata'] ?? null;

        if (isset($query['receivetime'])) {
            $this->receiveTime = \DateTimeImmutable::createFromFormat('U', $query['receivetime']);
            if ($this->receiveTime === false) {
                throw new InvalidWebhookException(
                    'The format of `receivetime` is wrong. Value given: '.$query['receivetime']
                );
            }
        }

        if (isset($query['statuscode'])) {
            $this->statusCode = (int)$query['statuscode'];
        }

        if (isset($query['logdate'])) {
            $this->logDate = \DateTimeImmutable::createFromFormat('Y_m_d', $query['logdate']);
            if ($this->logDate === false) {
                throw new InvalidWebhookException('The format of `logdate` is wrong. Value given: '.$query['logdate']);
            }
        }

        if (isset($query['mcc'])) {
            $this->mcc = (int)$query['mcc'];
        }

        if (isset($query['mnc'])) {
            $this->mnc = (int)$query['mnc'];
        }

        if (isset($query['batchid'])) {
            $this->batchId = (int)$query['batchid'];
        }

        if (isset($query['push_price'])) {
            $this->pushPrice = (int)$query['push_price'];
        }
    }

    /**
     * Returns true if the respective message was received by the recipient
     *
     * @return bool
     */
    public function isReceived() : bool
    {
        return $this->status === static::STATUS_RECEIVED;
    }

    /**
     * Returns true if the respective message was rejected
     *
     * @return bool
     */
    public function isRejected() : bool
    {
        return $this->status === static::STATUS_REJECTED;
    }

    /**
     * Returns true if the respective message is buffered
     *
     * @return bool
     */
    public function isBuffered() : bool
    {
        return $this->status === static::STATUS_BUFFERED;
    }

    /**
     * Returns true if the respective message has expired
     *
     * @return bool
     */
    public function isExpired() : bool
    {
        return $this->status === static::STATUS_EXPIRED;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getReceiveTime(): \DateTimeImmutable
    {
        return $this->receiveTime;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getReturnData(): string
    {
        return $this->returnData;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLogDate(): \DateTimeImmutable
    {
        return $this->logDate;
    }

    /**
     * @return int
     */
    public function getMcc(): int
    {
        return $this->mcc;
    }

    /**
     * @return int
     */
    public function getMnc(): int
    {
        return $this->mnc;
    }

    /**
     * @return int
     */
    public function getBatchId(): int
    {
        return $this->batchId;
    }

    /**
     * @return int
     */
    public function getPushPrice(): int
    {
        return $this->pushPrice;
    }
}
