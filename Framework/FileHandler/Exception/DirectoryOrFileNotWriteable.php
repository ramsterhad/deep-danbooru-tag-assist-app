<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Shared\Exception\FrameworkException;
use Throwable;

class DirectoryOrFileNotWriteable extends FrameworkException
{
    public function __construct($pathToFile, $message = "", $code = 0, Throwable $previous = null)
    {
        $specificMessage = sprintf(
            'Directory or File "%s" is not writeable.',
            $pathToFile
        );

        $message .= $specificMessage . ' ' . $message;

        parent::__construct($message, $code, $previous);
    }
}
