<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\System;


class Json
{
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}