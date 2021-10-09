<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\Filters;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Utility\StringUtils;

/**
 * Removes the rating tags.
 */
class FilterSafeTagsService
{
    public function filter(TagCollection $collection): TagCollection
    {
        $blacklist = [
            'rating:s',
            'rating:safe',
            'rating:q',
            'rating:questionable',
            'rating:e',
            'rating:explicit',
        ];

        $filtered = new TagCollection();

        foreach ($collection->getTags() as $tag) {
            if (StringUtils::strposArray($tag->getName(), $blacklist) === false) {
                $filtered->add($tag);
            }
        }

        return $filtered;
    }
}
