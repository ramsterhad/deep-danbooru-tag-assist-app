<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Shared\Exception\FrameworkException;
use Throwable;

use function sprintf;

class ParameterNotFoundException extends FrameworkException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Configuration parameter "%s" not found.', $message);
        parent::__construct($message, $code, $previous);
    }
}
