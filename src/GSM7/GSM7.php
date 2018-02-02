<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility\GSM7;

/**
 * See character set here
 *
 * @link https://en.wikipedia.org/wiki/GSM_03.38
 */
class GSM7
{
    // notice that we did not add the ESC, FF, SS2, and CR2 characters
    const ALPHABET = ["\n", "\r", ' ', '_', '-', ',', ';', ':', '!', '¡', '?', '¿', '.', "'", '"', '(', ')', '[', ']', '{', '}', '§', '@', '*', '/', '\\', '&', '#', '%', '^', '+', '<', '=', '>', '|', '~', '¤', '$', '£', '¥', '€', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'A', 'à', 'å', 'Å', 'ä', 'Ä', 'æ', 'Æ', 'b', 'B', 'c', 'C', 'Ç', 'd', 'D', 'e', 'E', 'é', 'É', 'è', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'ì', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'ñ', 'Ñ', 'o', 'O', 'ò', 'ö', 'Ö', 'ø', 'Ø', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 'ß', 't', 'T', 'u', 'U', 'ù', 'ü', 'Ü', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', 'Γ', 'Δ', 'Θ', 'Λ', 'Ξ', 'Π', 'Σ', 'Φ', 'Ψ', 'Ω'];
    const DOUBLES = ['|', '^', '€', '{', '}', '[', ']', '~', '\\'];

    public static function isGSM7(string $str) : bool
    {
        $alphabet = self::ALPHABET;

        $len = mb_strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($str, $i, 1);
            if (!in_array($char, $alphabet)) {
                return false;
            }
        }

        return true;
    }
}
