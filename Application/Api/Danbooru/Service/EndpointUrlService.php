<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

final class EndpointUrlService
{
    /**
     * In case the user has saved a custom URL, it has to be loaded from the cookie. Else the configured URL is load
     * from the environment file.
     *
     * @throws Exception
     */
    public function getEndpointAddress(): string
    {
        return $_COOKIE['danbooru_api_url'] ?? $this->getGetPostUrlFromConfig();
    }

    /**
     * Loads the URL from the environment file.
     *
     * @throws Exception
     */
    public function getGetPostUrlFromConfig(): string
    {
        return Config::get('danbooru_api_url') . 'posts.json?' . Config::get('danbooru_default_request');
    }
}