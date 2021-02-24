<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Collection;

interface ApiContract
{
    /**
     * Executes the command to receive the tags.
     */
    public function requestTags(): void;

    /**
     * @return Collection
     */
    public function getCollection(): Collection;

}