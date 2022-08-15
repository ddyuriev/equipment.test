<?php

namespace App\Helpers;


class RegexHelper
{
    /**
     * convert Mask To Regex
     *
     * @param $mask
     * @return string
     */
    public static function convertMaskToRegex($mask)
    {
        $chars = str_split($mask);
        $subRegEx = '';
        foreach ($chars as $char) {
            switch (true) {
                case $char === 'X':
                    $subRegEx .= '[0-9A-Z]';
                    break;
                case $char === 'A':
                    $subRegEx .= '[A-Z]';
                    break;
                case $char === 'N':
                    $subRegEx .= '[0-9]';
                    break;
                case $char === 'Z':
                    $subRegEx .= '[@\-_]';
                    break;
                case $char === 'a':
                    $subRegEx .= '[a-z]';
                    break;

            }
        }
        return '/^' . $subRegEx . '$/';
    }
}
