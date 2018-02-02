<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\ValueObject;

use Loevgaard\Linkmobility\GSM7\GSM7;

class Message extends StringValueObject
{
    /**
     * @var bool
     */
    private $gsm7;

    public function chunkCount() : int
    {
        $length = $this->length();
        $lengthThreshold = $this->isGsm7() ? 160 : 70;

        if ($length > $lengthThreshold) {
            $chunkDivisor = $this->isGsm7() ? 153 : 67;
            return (int)ceil($length / $chunkDivisor);
        }

        return 1;
    }

    public function length() : int
    {
        $length = mb_strlen($this->value);

        if (!$this->isGsm7()) {
            return $length;
        }

        $doubles = GSM7::DOUBLES;

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($this->value, $i, 1);
            if (in_array($char, $doubles)) {
                $length++;
            }
        }

        return $length;
    }

    public function isGsm7() : bool
    {
        if (is_null($this->gsm7)) {
            $this->gsm7 = GSM7::isGSM7($this->value);
        }

        return $this->gsm7;
    }

    public function isUnicode() : bool
    {
        return !$this->isGsm7();
    }
}
