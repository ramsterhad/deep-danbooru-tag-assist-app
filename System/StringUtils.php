<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\System;


class StringUtils
{
    /**
     * Stupid native function strpos can't work with multiple needles.
     *
     * @param string $haystack
     * @param string[] $needles
     * @return bool
     */
    public static function strposArray(string $haystack, array $needles): bool
    {
        foreach($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

}