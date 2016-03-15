<?php

namespace App\Services;

class StageTime
{
    public function fromString($string)
    {
        // mm:ss.xxx
        $parts = explode(':', $string);
        if (count($parts) == 2) {
            $minutes = $parts[0];
            $otherParts = explode('.', $parts[1]);
            $seconds = $otherParts[0];
            $milliseconds = $otherParts[1];

            $time = $milliseconds + ($seconds * 1000) + ($minutes * 1000 * 60);

            return $time;
        } else {
            return 0;
        }
    }

    public function toString($value)
    {
        if ($value && is_numeric($value)) {
            $milliseconds = $value % 1000;
            $seconds = (($value - $milliseconds) / 1000) % 60;
            $minutes = ($value - ($seconds * 1000) - $milliseconds) / (1000 * 60);

            return $minutes.':' .
                str_pad($seconds, 2, '0', STR_PAD_LEFT).'.' .
                str_pad($milliseconds, 3, '0', STR_PAD_LEFT);
        } else {
            if (is_string($value)) {
                return $value;
            } else {
                return '';
            }
        }
    }
}