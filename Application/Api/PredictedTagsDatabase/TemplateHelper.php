<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;


class TemplateHelper
{
    /**
     * @param TagCollection $machineLearningPlatform All tags found by the machine learning platform
     * @param TagCollection $filteredTags Reduced filter collection from the machine learning platform,
     *                                    reduced by Danbooru tags.
     *
     * @return string
     */
    public static function tagsCssClassHelperUnknownTags(TagCollection $machineLearningPlatform, TagCollection $filteredTags): string
    {
        $mlpTagList = '';

        foreach ($machineLearningPlatform->getTags() as $tag) {
            $isNew = false;

            foreach ($filteredTags->getTags() as $filteredTag) {
                if ($tag->getName() === $filteredTag->getName()) {
                    $isNew = true;
                    continue;
                }
            }

            if ($isNew) {
                $mlpTagList .= '<span class="tag unknownTag">' .$tag->getName() . '</span>';
            } else {
                $mlpTagList .= '<span class="tag">' . $tag->getName() . '</span>';
            }
        }

        return $mlpTagList;
    }

    public static function tagsCssClassHelperColoredDanbooruTags(Tag $tag): string
    {
        return '<span style="color: '. $tag->getHexColor().';">'.$tag->getName().'</span>';
    }
}