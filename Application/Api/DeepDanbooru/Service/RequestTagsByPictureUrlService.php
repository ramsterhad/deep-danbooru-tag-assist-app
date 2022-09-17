<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Service;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Infrastructure\DeepDanbooruRepository;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

final class RequestTagsByPictureUrlService
{
    public function __construct(
        private AdapterInterface $adapter,
        private DeepDanbooruRepository $deepDanbooruRepository,
    ) {}

    public function requestTags(string $pictureUrl): TagCollection
    {
        return $this->requestTagsFromRepository($pictureUrl);
    }

    private function requestTagsFromRepository($pictureUrl): TagCollection
    {
        return $this->deepDanbooruRepository->requestTagsByPictureUrl($this->adapter, $pictureUrl);
    }
}
