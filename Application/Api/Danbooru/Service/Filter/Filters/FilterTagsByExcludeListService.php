<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\TagExcludeList;

/**
 * Tags can be excluded by the user.
 */
class FilterTagsByExcludeListService
{
    public function filter(
        TagCollection $collection,
        TagExcludeList $excludeList
    ): TagCollection {

        $filtered = new TagCollection();

        foreach ($collection->getTags() as $tag) {
            if (!\in_array($tag->getName(), $excludeList->getList())) {
                $filtered->add($tag);
            }
        }

        return $filtered;
    }
}
