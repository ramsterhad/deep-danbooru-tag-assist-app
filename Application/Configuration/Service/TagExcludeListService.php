<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Service;

use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

class TagExcludeListService
{
    /**
     * @return string[]
     */
    public function getList(): array
    {
        $excludeListAsString = $this->readConfigValue();
        return $this->transformStringListToArray($excludeListAsString);
    }

    protected function readConfigValue(): string
    {
        return Config::getInstance()->get('tag_suggestion_exclude_list');
    }

    protected function transformStringListToArray(string $excludeList): array
    {
        $list = preg_split('/ /', $excludeList);
        return array_map(function ($value) {
            return trim($value);
        }, $list);
    }
}
