<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Logger;


class RequestLogger extends Logger
{
    protected function getDefaultDestinationFile(): string
    {
        return 'request.log';
    }
}
