<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\DanbooruApiBridge;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Endpoint;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AuthenticationError;

/**
 * Bridge to the service \Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Danbooru.
 *
 * Class DanbooruApiBridge
 * @package Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\DanbooruApiBridge
 */
class DanbooruApiBridge
{
    /**
     * The url is always read from the config as this request needs to be addressed to the configured target platform
     * (live or test).
     *
     * @param string $user
     * @param string $key
     * @return bool
     * @throws AuthenticationError
     */
    /*
    public function authenticate(string $user, string $key): bool
    {
        $danbooru = new Danbooru();
        return $danbooru->authenticate(new Endpoint(), $user, $key);
    }
    */
}