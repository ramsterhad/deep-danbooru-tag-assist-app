<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;

class TagExcludeList implements TagExcludeListInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string[]
     */
    public function getList(): array
    {
        $excludeListAsString = $this->readConfigValue();
        return $this->transformStringListToArray($excludeListAsString);
    }

    /**
     * @throws ParameterNotFoundException
     */
    protected function readConfigValue(): string
    {
        return $this->configuration->get('tag_suggestion_exclude_list');
    }

    protected function transformStringListToArray(string $excludeList): array
    {
        $list = preg_split('/ /', $excludeList);
        return array_map(function ($value) {
            return trim($value);
        }, $list);
    }
}
