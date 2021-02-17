<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Api;


use Ramsterhad\DeepDanbooruTagAssist\Api\Tag\Collection;

interface ApiContract
{
    /**
     * Sets the URL for the Danbooru API and the command for the MLP.
     */
    public static function loadEndpointAddress(): string;

    /**
     * Executes the command to receive the tags.
     */
    public function callForTags(): void;

    /**
     * @return Collection
     */
    public function getCollection(): Collection;

}