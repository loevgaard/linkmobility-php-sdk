<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\Response;

use Assert\Assert;
use Loevgaard\Linkmobility\Response\BatchStatusResponse\Details;
use Loevgaard\Linkmobility\Response\BatchStatusResponse\Stat;

class BatchStatusResponse extends Response
{
    /**
     * @var Stat
     */
    protected $stat;

    /**
     * @var Details
     */
    protected $details;

    /**
     * @var int
     */
    protected $status;

    public function init() : void
    {
        Assert::that($this->data)
            ->isArray()
            ->keyExists('status')
            ->keyExists('stat')
            ->keyExists('details')
        ;

        $this->status = (int)$this->data['status'];
        $this->stat = new Stat($this->data['stat']);
        $this->details = new Details($this->data['details']);

        $this->successful = $this->status >= 200 && $this->status < 300;
    }

    /**
     * @return Stat
     */
    public function getStat(): Stat
    {
        return $this->stat;
    }

    /**
     * @return Details
     */
    public function getDetails(): Details
    {
        return $this->details;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
