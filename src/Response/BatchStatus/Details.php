<?php
namespace Loevgaard\Linkmobility\Response\BatchStatus;

use Loevgaard\Linkmobility\Exception\InvalidResponseException;
use Loevgaard\Linkmobility\Response\Response;

class Details extends Response
{
    /**
     * The batch is ready for processing. The batch will be processed shortly after sendtime has passed.
     */
    const STATE_QUEUED = 'QUEUED';

    /**
     * The batch is currently processing. Messages are being sent, and the "buffered",
     * "received" and "rejected" numbers will be updated as it is processed.
     */
    const STATE_RUNNING = 'RUNNING';

    /**
     * The batch has finished processing. Up to 48 hours after this the messages will still be sent out for delivery.
     */
    const STATE_DONE = 'DONE';

    /**
     * @var \DateTimeImmutable
     */
    protected $sendTime;

    /**
     * @var int
     */
    protected $batchId;

    /**
     * @var string
     */
    protected $state;

    /**
     * @throws InvalidResponseException
     */
    public function init() : void
    {
        if (isset($this->data->sendtime)) {
            $this->sendTime = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $this->data->sendtime);
            if ($this->sendTime === false) {
                throw new InvalidResponseException(
                    '`sendtime` does not have the correct format. Value given: '.$this->data->sendtime
                );
            }
        }

        if (isset($this->data->batchid)) {
            $this->batchId = (int)$this->data->batchid;
        }

        $this->state = $this->data->state ?? null;
    }

    /**
     * @return bool
     */
    public function isDone() : bool
    {
        return $this->state === static::STATE_DONE;
    }

    /**
     * @return bool
     */
    public function isQueued() : bool
    {
        return $this->state === static::STATE_QUEUED;
    }

    /**
     * @return bool
     */
    public function isRunning() : bool
    {
        return $this->state === static::STATE_RUNNING;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getSendTime(): \DateTimeImmutable
    {
        return $this->sendTime;
    }

    /**
     * @return int
     */
    public function getBatchId(): int
    {
        return $this->batchId;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
