<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters\FilterTagsByAlreadyKnownTagsService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

class FilterAlreadyKnownTags
{
    private FilterTagsByAlreadyKnownTagsService $filterTagsByAlreadyKnownTagsService;

    public function __construct(
        FilterTagsByAlreadyKnownTagsService $filterTagsByAlreadyKnownTagsService,
    ) {
        $this->filterTagsByAlreadyKnownTagsService = $filterTagsByAlreadyKnownTagsService;
    }

    public function filter(TagCollection $collection, Post $post): TagCollection
    {
        return $this->filterTagsByAlreadyKnownTagsService->filter($collection, $post);
    }
}
