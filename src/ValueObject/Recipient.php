<?php declare(strict_types = 1);
namespace Loevgaard\Linkmobility\ValueObject;

use Assert\Assert;

class Recipient extends StringValueObject
{
    public function __construct(string $value)
    {
        Assert::that($value)->regex('/^(\+|c)?[0-9]+$/i');

        parent::__construct($value);
    }
}
