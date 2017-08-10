<?php
namespace Loevgaard\Linkmobility\Payload;

interface PayloadInterface
{
    /**
     * Will return an array with the payload
     *
     * @return array
     */
    public function getPayload() : array;

    /**
     * Will return true if payload is valid
     * It will follow
     *
     * @return boolean
     */
    public function validate() : bool;
}
