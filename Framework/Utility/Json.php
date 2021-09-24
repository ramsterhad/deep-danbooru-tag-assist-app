<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Utility;


use function json_decode;
use function json_last_error;

class Json
{
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}