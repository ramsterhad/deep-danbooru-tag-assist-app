<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Logger;


class ErrorLogger extends Logger
{
    public static function getDefaultDestinationFile(): string
    {
        return 'error.log';
    }
}
