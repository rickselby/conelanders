<?php

namespace App\Services;

class StageTime
{
    public function fromString($string)
    {
        // mm:ss.xxx
        $parts = explode(':', $string);
        $minutes = $parts[0];
        $otherParts = explode('.', $parts[1]);
        $seconds = $otherParts[0];
        $milliseconds = $otherParts[1];

        $time = $milliseconds + ($seconds * 1000) + ($minutes * 1000 * 60);

        return $time;
    }

    public function toString($int)
    {
        $milliseconds = $int % 1000;
        $seconds = (($int - $milliseconds) / 1000) % 60;
        $minutes = ($int - ($seconds * 1000) - $milliseconds) / (1000 * 60);

        return $minutes.
            ':'.
            str_pad($seconds, 2, '0', STR_PAD_LEFT).
            '.'.
            str_pad($milliseconds, 3, '0', STR_PAD_LEFT);
    }
}