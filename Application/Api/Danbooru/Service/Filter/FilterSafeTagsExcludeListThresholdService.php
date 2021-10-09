<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters\FilterSafeTagsService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters\FilterTagsByExcludeListService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters\FilterTagsByScoreService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\TagExcludeList;

/**
 * Removes all tags with a score lass than $tags_min_score (see config).
 */
class FilterSafeTagsExcludeListThresholdService
{
    private FilterSafeTagsService $filterSafeTags;
    private FilterTagsByExcludeListService $filterTagsByExcludeListService;
    private FilterTagsByScoreService $filterTagsByScoreService;
    private TagExcludeList $tagExcludeListService;

    public function __construct(
        FilterSafeTagsService $filterSafeTags,
        FilterTagsByExcludeListService $filterTagsByExcludeList,
        FilterTagsByScoreService $filterTagsByScoreService,
        TagExcludeList $tagExcludeListService,
    ) {
        $this->filterSafeTags = $filterSafeTags;
        $this->filterTagsByExcludeListService = $filterTagsByExcludeList;
        $this->filterTagsByScoreService = $filterTagsByScoreService;
        $this->tagExcludeListService = $tagExcludeListService;
    }

    public function filter(TagCollection $collection, Post $post): TagCollection
    {
        $collection = $this->filterSafeTags->filter($collection);
        $collection = $this->filterTagsByExcludeListService->filter($collection, $this->tagExcludeListService);
        $collection = $this->filterTagsByScoreService->filter($collection);

        return $collection;
    }
}
