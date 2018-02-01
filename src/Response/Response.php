<?php
namespace Loevgaard\Linkmobility\Response;

abstract class Response implements ResponseInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;

        $this->init();
    }
}
