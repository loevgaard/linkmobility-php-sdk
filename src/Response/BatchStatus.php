<?php
namespace Loevgaard\Linkmobility\Response;

use Loevgaard\Linkmobility\Response\BatchStatus\Details;
use Loevgaard\Linkmobility\Response\BatchStatus\Stat;

class BatchStatus extends Response
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
        if (isset($this->data->status)) {
            $this->status = (int)$this->data->status;
        }

        if (isset($this->data->stat)) {
            $this->stat = new Stat($this->data->stat);
        }

        if (isset($this->data->details)) {
            $this->details = new Details($this->data->details);
        }
    }

    /**
     * Returns true if the request was successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->status >= 200 && $this->status < 300;
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
