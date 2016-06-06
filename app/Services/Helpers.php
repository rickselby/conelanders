<?php

namespace App\Services;

/**
 * Class Helpers
 * This class DOES NOT USE A FACADE so declare functions as static
 * @package App\Services
 */
class Helpers
{
    public static function getInitials($string)
    {
        if (strlen($string) > 10) {
            $words = explode(' ', $string);
            $initials = [];
            foreach($words AS $word) {
                $initials[] = $word[0].'.';
            }
            return implode(' ', $initials);
        } else {
            return $string;
        }
    }
}