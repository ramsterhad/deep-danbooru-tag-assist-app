<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;

use function floatval;

/**
 * Removes all tags with a score lass than $tags_min_score (see config).
 */
class FilterTagsByScoreService
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @throws ParameterNotFoundException
     */
    public function filter(TagCollection $collection): TagCollection
    {
        $filtered = new TagCollection();

        foreach ($collection->getTags() as $tag) {

            $tagScore = floatval($tag->getScore());
            $configScore = floatval($this->configuration->get('tags_min_score'));

            if ($tagScore >= $configScore) {
                $filtered->add($tag);
            }
        }

        return $filtered;
    }
}
