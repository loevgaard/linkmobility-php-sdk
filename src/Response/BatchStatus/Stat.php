<?php
namespace Loevgaard\Linkmobility\Response\BatchStatus;

use Loevgaard\Linkmobility\Exception\InvalidResponseException;
use Loevgaard\Linkmobility\Response\Response;

class Stat extends Response
{
    /**
     * @var \DateTimeImmutable
     */
    protected $sendTime;

    /**
     * @var int
     */
    protected $buffered;

    /**
     * @var int
     */
    protected $received;

    /**
     * @var int
     */
    protected $rejected;

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

        if (isset($this->data->buffered)) {
            $this->buffered = (int)$this->data->buffered;
        }

        if (isset($this->data->received)) {
            $this->received = (int)$this->data->received;
        }

        if (isset($this->data->rejected)) {
            $this->rejected = (int)$this->data->rejected;
        }
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
    public function getBuffered(): int
    {
        return $this->buffered;
    }

    /**
     * @return int
     */
    public function getReceived(): int
    {
        return $this->received;
    }

    /**
     * @return int
     */
    public function getRejected(): int
    {
        return $this->rejected;
    }
}
