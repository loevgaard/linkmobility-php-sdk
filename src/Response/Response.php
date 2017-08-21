<?php
namespace Loevgaard\Linkmobility\Response;

abstract class Response
{
    /**
     * @var \stdClass
     */
    protected $data;

    public function __construct(\stdClass $data)
    {
        $this->data = $data;

        $this->init();
    }

    public function init()
    {
    }

    /**
     * @return \stdClass
     */
    public function getData(): \stdClass
    {
        return $this->data;
    }
}
