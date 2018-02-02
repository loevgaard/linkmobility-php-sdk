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

    public function init() : void
    {
        Assert::that($this->data)
            ->keyExists('stat')
            ->keyExists('details')
        ;

        $this->stat = new Stat($this->data['stat']);
        $this->details = new Details($this->data['details']);
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
}
