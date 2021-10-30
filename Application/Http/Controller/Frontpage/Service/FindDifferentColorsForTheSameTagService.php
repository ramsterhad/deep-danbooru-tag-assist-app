<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage\Service;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage\DataType\TagDecorator;

class FindDifferentColorsForTheSameTagService
{
    public function highlightTags(TagCollection $decoratedSuggestedTags, Post $post): void
    {
        /** @var TagDecorator $tag */
        foreach ($decoratedSuggestedTags->getTags() as $tag) {

            if ($tag->isColored()) {

                foreach ($post->getTags() as $danbooruTag) {

                    /*
                     * Those queries are split, because if the second one hits the loop can jump to the next item
                     * independently if it was true or not, since it had already the check with the tag with the same name.
                     */
                    if ($danbooruTag->isColored()) {

                        if ($tag->getNameWithoutColor() === $danbooruTag->getNameWithoutColor()) {

                            if ($tag->getColor() !== $danbooruTag->getColor()) {
                                $danbooruTag->setHightlightColoredTag(true);
                                $tag->setHightlightColoredTag(true);
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
}
