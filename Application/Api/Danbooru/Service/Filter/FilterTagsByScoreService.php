<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

/**
 * Removes all tags with a score lass than $tags_min_score (see config).
 */
class FilterTagsByScoreService
{
    public function filter(TagCollection $collection): TagCollection
    {
        $filtered = new TagCollection();

        foreach ($collection->getTags() as $tag) {

            $tagScore = \floatval($tag->getScore());
            $configScore = \floatval(Config::get('tags_min_score'));

            if ($tagScore >= $configScore) {
                $filtered->add($tag);
            }
        }

        return $filtered;
    }
}
