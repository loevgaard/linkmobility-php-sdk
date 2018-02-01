<?php
namespace Loevgaard\Linkmobility\Response;

interface ResponseInterface
{
    /**
     * Should instantiate the object from the data given
     */
    public function init() : void;
}
