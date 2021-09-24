<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Utility;


use function str_contains;

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
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

}