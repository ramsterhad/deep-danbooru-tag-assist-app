<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Shared\Exception\FrameworkException;

class DownloadPictureException extends FrameworkException
{
    const MESSAGE_EMPTYPICTURE = 'Picture data is empty. Seems like nothing was downloaded.';
    const MESSAGE_EMPTYPATHTOFILE = 'Picture could not be saved.';
}