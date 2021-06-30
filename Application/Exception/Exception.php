<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Exception;


class Exception extends \Exception
{
    public function getStacktraceWithCode(): string
    {
        return \sprintf('code: %d%s%s', $this->getCode(), \PHP_EOL, $this->getTraceAsString());
    }
}