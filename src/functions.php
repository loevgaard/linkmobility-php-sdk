<?php
namespace Loevgaard\Linkmobility;

function chunkCount(string $message, $unicode = false) : int
{
    $chunkCount = 1;
    $length = messageLength($message, $unicode);

    if ($unicode && $length > 70) {
        $chunkCount = ceil($length / 67);
    } elseif ($length > 160) {
        $chunkCount = ceil($length / 153);
    }

    return $chunkCount;
}

function messageLength($message, $unicode = false) : int
{
    $length = mb_strlen($message);

    if ($unicode) {
        return $length;
    }

    $doubles = ['|', '^', '€', '{', '}', '[', ']', '~', '\\'];
    $addToLength = 0;

    for ($i = 0; $i < $length; $i++) {
        $char = mb_substr($message, $i, 1);
        if (in_array($char, $doubles)) {
            $addToLength++;
        }
    }

    $length += $addToLength;

    return $length;
}
