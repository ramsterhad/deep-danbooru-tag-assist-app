<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Api\MachineLearningPlatform;


use Ramsterhad\DeepDanbooruTagAssist\Api\Tag\Collection;


class TemplateHelper
{
    /**
     * @param Collection $machineLearningPlatform All tags found by the machine learning platform
     * @param Collection $filteredTags Reduced filter collection from the machine learning platform,
     *                                 reduced by Danbooru tags.
     *
     * @return string
     */
    public static function tagsCssClassHelper(Collection $machineLearningPlatform, Collection $filteredTags): string
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
}