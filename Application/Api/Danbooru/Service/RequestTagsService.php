<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure\DanbooruRepository;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\MarkTagByColorAttributeService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

final class RequestTagsService
{
    public function __construct(
        private AdapterInterface $adapter,
        private DanbooruRepository $danbooruRepository
    ) {}

    public function requestTags(
        Post $post,
        string $url,
        MarkTagByColorAttributeService $markTagByColorAttributeService
    ): TagCollection {

        $tagCollection = $this->danbooruRepository->requestTagsByPost($this->adapter, $url, $post);
        $collection = new TagCollection();

        foreach ($tagCollection->getTags() as $tag) {
            $markTagByColorAttributeService->checkAndActivateHighlighting($tag);
            $collection->add($tag);
        }

        return $collection;
    }
}
