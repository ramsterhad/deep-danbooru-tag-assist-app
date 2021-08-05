<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Frontpage;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\TagExcludeListInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\System\StringUtils;

class Filter
{
    /**
     * This function compares the Danbooru tags with given ones and returns a tag collection of unknown tags.
     *
     * @param TagCollection $suggestedTags
     * @param TagCollection $danbooruTags
     * @return TagCollection
     */
    public function filterTagsAgainstAlreadyKnownTags(
        TagCollection $suggestedTags,
        TagCollection $danbooruTags
    ): TagCollection {
        $unknownTagCollection = new TagCollection();

        foreach ($suggestedTags->getTags() as $tag) {

            $knownTag = false; //Unknown by default, unless proven known

            foreach ($danbooruTags->getTags() as $danbooruTag) {

                if (trim($danbooruTag->getName()) === trim($tag->getName())) {
                    // Tag is already known on danbooru:
                    $knownTag = true;
                    continue;
                }
            }

            // Add unknown (!known) tags to $unknownTagCollection
            if (!$knownTag) {
                $unknownTagCollection->add($tag);
            }
        }

        return $unknownTagCollection;
    }

    /**
     * Filters the rating tags from the result.
     *
     * @param TagCollection $collection
     * @return TagCollection
     */
    public function filterSafeTags(TagCollection $collection): TagCollection
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

    public function filterTagsByScore(TagCollection $collection): TagCollection
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

    public function filterTagsByExcludeList(TagCollection $collection, TagExcludeListInterface $excludeList): TagCollection
    {
        $filtered = new TagCollection();

        foreach ($collection->getTags() as $tag) {
            if (!\in_array($tag->getName(), $excludeList->getList())) {
                $filtered->add($tag);
            }
        }

        return $filtered;
    }
}
