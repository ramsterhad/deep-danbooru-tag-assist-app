<?php

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\DataType\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\DownloadPictureException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory\SplFileObjectFactory;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure\DanbooruRepository;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Service\TemporaryFileService;

use function basename;

class DownloadPictureService
{
    private AdapterInterface $adapter;
    private DanbooruRepository $repository;
    private TemporaryFileService $temporaryFileService;

    public function __construct(
        AdapterInterface     $adapter,
        DanbooruRepository   $repository,
        TemporaryFileService $temporaryFileService
    ) {
        $this->adapter = $adapter;
        $this->repository = $repository;
        $this->temporaryFileService = $temporaryFileService;
    }

    /**
     * @throws Exception
     */
    public function download(Post $post): Picture
    {
        $url = $post->getPicOriginal();
        $filename = basename($url);

        $pictureData = $this->repository->downloadPicture($this->adapter, $url);
        $pathToFile = $this->temporaryFileService->writeFileToTemporaryDirectory($filename, $pictureData);

        if (empty($pictureData)) {
            throw new DownloadPictureException(DownloadPictureException::MESSAGE_EMPTYPICTURE);
        }

        if (empty($pathToFile)) {
            throw new DownloadPictureException(DownloadPictureException::MESSAGE_EMPTYPATHTOFILE);
        }

        $dataType = new Picture();
        $dataType->setFile(SplFileObjectFactory::create($pathToFile));

        return $dataType;
    }
}
