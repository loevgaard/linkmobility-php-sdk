<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response\BatchStatusResponse;

use Assert\Assert;

class Stat
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

    public function __construct(array $data)
    {
        Assert::that($data)
            ->isArray()->keyExists('sendtime')
            ->keyExists('buffered')
            ->keyExists('received')
            ->keyExists('rejected')
        ;

        $this->sendTime = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $data['sendtime']);
        Assert::that($this->sendTime)->isInstanceOf(\DateTimeImmutable::class, '`sendtime` does not have the correct format. Value given: '.$data['sendtime']);

        $this->buffered = (int)$data['buffered'];
        $this->received = (int)$data['received'];
        $this->rejected = (int)$data['rejected'];
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
