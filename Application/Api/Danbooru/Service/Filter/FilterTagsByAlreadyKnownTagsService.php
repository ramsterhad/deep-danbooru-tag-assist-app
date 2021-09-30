<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

/**
 * Filter the known tags from Danbooru against the suggested tags and return the difference.
 * The unknown tags are later listed and registered with the numpad keys.
 */
class FilterTagsByAlreadyKnownTagsService
{
    /**
     * This function compares the Danbooru tags with given ones and returns a tag collection of unknown tags.
     */
    public function filter(
        TagCollection $suggestedTags,
        Post $post
    ): TagCollection {

        $unknownTagCollection = new TagCollection();

        foreach ($suggestedTags->getTags() as $tag) {

            $knownTag = false; //Unknown by default, unless proven known

            foreach ($post->getTagCollection()->getTags() as $danbooruTag) {

                if (trim($danbooruTag->getName()) === trim($tag->getName())) {
                    // Tag is already known on danbooru:
                    $knownTag = true;
                    break;
                }
            }

            // Add unknown (!known) tags to $unknownTagCollection
            if (!$knownTag) {
                $unknownTagCollection->add($tag);
            }
        }

        return $unknownTagCollection;
    }
}