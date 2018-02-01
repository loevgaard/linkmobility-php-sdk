<?php declare(strict_types = 1);
namespace Loevgaard\Linkmobility\ValueObject;

use Assert\Assert;

abstract class StringValueObject extends ValueObject
{
    public function __construct(string $value)
    {
        Assert::that($value)->notEmpty();

        $this->value = $value;
    }

    public function __toString()
    {
        return $this->get();
    }

    public function get() : string
    {
        return $this->value;
    }
}
