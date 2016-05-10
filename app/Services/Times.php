<?php

namespace App\Services;

class Times
{
    public function fromString($string)
    {
        // (hh:)mm:ss.xxx
        $parts = explode(':', $string);
        if (count($parts) == 2 || count($parts) == 3) {
            if (count($parts) == 3) {
                $hours = $parts[0];
                $minutes = $parts[1];
                $secondsPart = $parts[2];
            } else {
                $hours = 0;
                $minutes = $parts[0];
                $secondsPart = $parts[1];
            }
            $otherParts = explode('.', $secondsPart);
            $seconds = $otherParts[0];
            $milliseconds = $otherParts[1];

            $time = $milliseconds + ($seconds * 1000) + ($minutes * 1000 * 60) + ($hours * 1000 * 60 * 60);

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
            if ($minutes > 59) {
                $minutes = $minutes % 60;
                $hours = ($value - ($minutes * (1000 * 60)) - ($seconds * 1000) - $milliseconds) / (1000 * 60 * 60);
                $hoursMinutesString = $hours.':'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
            } else {
                $hoursMinutesString = $minutes;
            }

            return $hoursMinutesString.':'
                .str_pad($seconds, 2, '0', STR_PAD_LEFT).'.'
                .str_pad($milliseconds, 3, '0', STR_PAD_LEFT);
        } else {
            if (is_string($value)) {
                return $value;
            } else {
                return '';
            }
        }
    }
}
