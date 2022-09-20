<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Service;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Infrastructure\DeepDanbooruRepository;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Exception\AdapterApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

final class RequestTagsByPictureUrlService
{
    public function __construct(
        private AdapterInterface $adapter,
        private DeepDanbooruRepository $deepDanbooruRepository,
    ) {}

    /**
     * @throws AdapterApplicationException
     */
    public function requestTags(string $pictureUrl): TagCollection
    {
        return $this->requestTagsFromRepository($pictureUrl);
    }

    /**
     * @throws AdapterApplicationException
     */
    private function requestTagsFromRepository($pictureUrl): TagCollection
    {
        return $this->deepDanbooruRepository->requestTagsByPictureUrl($this->adapter, $pictureUrl);
    }
}
