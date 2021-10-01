<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Exception;

use Throwable;

use function sprintf;

class TemplateVariableNotFoundException extends RouterException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Missing template variable for "%s".', $message);
        parent::__construct($message, $code, $previous);
    }
}
