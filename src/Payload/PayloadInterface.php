<?php
namespace Loevgaard\Linkmobility\Payload;

use Loevgaard\Linkmobility\Exception\InvalidPayloadException;

interface PayloadInterface
{
    /**
     * Will return an array with the payload
     *
     * @return array
     */
    public function getPayload() : array;

    /**
     * Must throw InvalidPayloadException if not valid
     *
     * @throws InvalidPayloadException
     * @return void
     */
    public function validate() : void;
}
