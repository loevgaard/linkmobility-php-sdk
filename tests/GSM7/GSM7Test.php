<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\GSM7;

use PHPUnit\Framework\TestCase;

class GSM7Test extends TestCase
{
    public function testIsGSM7()
    {
        $tests = [
            ['string' => 'abc', 'expected' => true],
            ['string' => 'abc', 'expected' => true],
            ['string' => "Hello \xF0\x9F\x98\x83", 'expected' => false],
            ['string' => "\r\n", 'expected' => true],
            ['string' => ' _-,;:!¡?¿.\',"()[]{}§@*/\\&#%^+<=>|~¤$£¥€0123456789aAàåÅäÄæÆbBcCÇdDeEéÉèfFgGhHiIìjJkKlLmMnNñÑoOòöÖøØpPqQrRsSßtTuUùüÜvVwWxXyYzZΓΔΘΛΞΠΣΦΨΩ', 'expected' => true],
        ];

        foreach ($tests as $test) {
            $this->assertSame($test['expected'], GSM7::isGSM7($test['string']));
        }
    }
}
