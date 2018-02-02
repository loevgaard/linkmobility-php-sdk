<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response\BatchStatusResponse;

use Assert\Assert;
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

    public function init() : void
    {
        Assert::that($this->data)
            ->isArray()->keyExists('sendtime')
            ->keyExists('buffered')
            ->keyExists('received')
            ->keyExists('rejected')
        ;

        $this->sendTime = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $this->data['sendtime']);
        Assert::that($this->sendTime)->isInstanceOf(\DateTimeImmutable::class, '`sendtime` does not have the correct format. Value given: '.$this->data['sendtime']);

        $this->buffered = (int)$this->data['buffered'];
        $this->received = (int)$this->data['received'];
        $this->rejected = (int)$this->data['rejected'];
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
