<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Shared\Exception;

class ApplicationException extends \Exception
{
    public function getStacktraceWithCode(): string
    {
        return \sprintf('code: %d,%s%s%s', $this->getCode(), $this->getMessage(), \PHP_EOL, $this->getTraceAsString());
    }
}
