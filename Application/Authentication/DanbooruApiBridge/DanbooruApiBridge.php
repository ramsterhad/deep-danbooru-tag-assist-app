<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\DanbooruApiBridge;

use Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Configuration\Config;

/**
 * Bridge to the service \Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Danbooru.
 *
 * Class DanbooruApiBridge
 * @package Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\DanbooruApiBridge
 */
class DanbooruApiBridge
{
    public function authenticate(string $user, string $key): bool
    {
        $danbooru = new Danbooru(Config::get('danbooru_api_url'));
        return $danbooru->authenticate($user, $key);
    }
}