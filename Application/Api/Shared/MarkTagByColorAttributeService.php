<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagInterface;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;

class MarkTagByColorAttributeService
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function checkAndActivateHighlighting(TagInterface $tag): void
    {
        $list = $this->configuration->get('highlight_color_attributes');
        $colors = $this->transformStringListToArray($list);

        foreach ($colors as $color) {

            if (str_contains($tag->getName(), $color)) {
                $tag->setColor($color);
                $tag->setIsColored(true);
                break;
            }
        }
    }

    protected function transformStringListToArray(string $excludeList): array
    {
        $list = preg_split('/,/', $excludeList);
        return array_map(function ($value) {
            return trim($value);
        }, $list);
    }
}