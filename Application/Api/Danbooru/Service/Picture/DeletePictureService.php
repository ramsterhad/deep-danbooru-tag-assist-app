<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\DataType\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory\SplFileObjectFactory;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Service\TemporaryFileService;

use function unlink;

class DeletePictureService
{
    private TemporaryFileService $temporaryFileService;

    public function __construct(
        TemporaryFileService $temporaryFileService
    ) {
        $this->temporaryFileService = $temporaryFileService;
    }

    public function delete(Picture $picture): bool
    {
        $return = unlink($picture->getFile()->getPathname());
        $picture->destroyFileObject();
        return $return;
    }
}