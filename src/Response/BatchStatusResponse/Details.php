<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response\BatchStatusResponse;

use Assert\Assert;
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

    public function init() : void
    {
        Assert::that($this->data)
            ->isArray()->keyExists('sendtime')
            ->keyExists('batchid')
            ->keyExists('state')
        ;

        Assert::that($this->data['state'])->choice(self::getStates());

        $this->sendTime = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $this->data['sendtime']);
        Assert::that($this->sendTime)->isInstanceOf(\DateTimeImmutable::class, '`sendtime` does not have the correct format. Value given: '.$this->data['sendtime']);

        $this->batchId = (int)$this->data['batchid'];
        $this->state = $this->data['state'];
    }

    public static function getStates() : array
    {
        return [
            self::STATE_DONE => self::STATE_DONE,
            self::STATE_QUEUED => self::STATE_QUEUED,
            self::STATE_RUNNING => self::STATE_RUNNING,
        ];
    }

    public function isState(string $state) : bool
    {
        return $this->state === $state;
    }

    /**
     * @return bool
     */
    public function isDone() : bool
    {
        return $this->isState(self::STATE_DONE);
    }

    /**
     * @return bool
     */
    public function isQueued() : bool
    {
        return $this->isState(self::STATE_QUEUED);
    }

    /**
     * @return bool
     */
    public function isRunning() : bool
    {
        return $this->isState(self::STATE_RUNNING);
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
