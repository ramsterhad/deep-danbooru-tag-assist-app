<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\ApiContract;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Application;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

class MachineLearningPlatform implements ApiContract
{
    private TagCollection $collection;

    private Picture $picture;

    /**
     * The function triggers an analysis by a machine learning platform. The call returns a simple array with multiple
     * entries; confidence scores and tags, so we have to filter the output accordingly. It also filters specific tags
     * by blacklist.
     */
    public function requestTags(): void
    {
        if (Config::get('machine_learning_platform_repository_debug') === false) {
            exec('bash ' . Application::getBasePath() . 'ml.sh '.$this->picture->getFullPathToFile().' 0.500', $output);
        } else {
            // Placeholder array, replaces the above exec()
            $output = [
                'Tags of /mnt/f/deepdanbooru/sample-460c5595dcf9b07d58f951d349202d98.jpg:',
                '(0.997) 1girl',
                '(0.706) bangs',
                '(0.840) blurry',
                '(0.943) bow',
                '(0.640) brown_eyes',
                '(0.968) brown_hair',
                '(0.826) eyebrows_visible_through_hair',
                '(0.548) frilled_bow',
                '(0.753) frills',
                '(0.905) hair_bow',
                '(0.990) hair_tubes',
                '(0.596) long_hair',
                '(0.904) looking_at_viewer',
                '(0.731) red_bow',
                '(0.992) solo',
                '(1.000) hakurei_reimu',
                '(1.000) rating:safe',
                '',
            ];
        }

        $tags = [];
        foreach ($output as $item) {

            // Filters for ( as we only want tags with a score.
            if (strpos($item, '(') !== false) {
                $item = preg_split('/ /', $item);
                $item[0] = str_replace(['(', ')'], '', $item[0]);
                $tags[] = $item;
            }
        }

        arsort($tags);
        $this->collection = new TagCollection();

        foreach ($tags as $tag) {
            $this->collection->add(new Tag($tag[1], $tag[0]));
        }
    }

    public function getCollection(): TagCollection
    {
        return $this->collection;
    }

    public function setPicture(Picture $picture): void
    {
        $this->picture = $picture;
    }

    public function getPicture(): Picture
    {
        return $this->picture;
    }
}
