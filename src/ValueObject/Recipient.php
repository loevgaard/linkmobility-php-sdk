<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use Assert\Assert;

class Recipient extends StringValueObject
{
    public function __construct(string $value)
    {
        // valid formats can be found here: https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017807/08.+Messages#id-08.Messages-recipients
        Assert::that($value)->regex('/^(\+|c)?[0-9]+$/i');

        parent::__construct($value);
    }
}
