<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Logger;


class StatisticLogger extends Logger
{
    public function getDefaultDestinationFile(): string
    {
        return 'statistics.log';
    }
}