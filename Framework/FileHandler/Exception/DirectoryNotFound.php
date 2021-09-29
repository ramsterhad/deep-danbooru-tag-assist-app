<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Shared\Exception\FrameworkException;
use Throwable;

class DirectoryNotFound extends FrameworkException
{
    public function __construct($pathToStorage, $message = "", $code = 0, Throwable $previous = null)
    {
        $specificMessage = sprintf(
            'Directory "%s" was not found. Be sure that it exists and is writeable',
            $pathToStorage
        );

        $message .= $specificMessage . ' ' . $message;

        parent::__construct($message, $code, $previous);
    }
}
