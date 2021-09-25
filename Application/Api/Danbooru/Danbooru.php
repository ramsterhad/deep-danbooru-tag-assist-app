<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

class Danbooru
{
    /**
     * @param Endpoint $endpoint
     * @param int $id
     * @param TagCollection $collection
     * @throws Exception\EndpointException
     */
    public function pushTags(Endpoint $endpoint, int $id, TagCollection $collection ): void
    {
        $endpoint->pushTags(
            Config::get('danbooru_api_url'),
            Session::get('username'),
            Session::get('api_key'),
            $id,
            $collection
        );
    }
}
