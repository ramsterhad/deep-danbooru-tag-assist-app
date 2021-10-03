<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;

final class EndpointUrlService
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * In case the user has saved a custom URL, it has to be loaded from the cookie. Else the configured URL is load
     * from the environment file.
     *
     * @throws ParameterNotFoundException
     */
    public function getEndpointAddress(): string
    {
        return $_COOKIE['danbooru_api_url'] ?? $this->getGetPostUrlFromConfig();
    }

    /**
     * Loads the URL from the environment file.
     *
     * @throws ParameterNotFoundException
     */
    public function getGetPostUrlFromConfig(): string
    {
        return $this->configuration->get('danbooru_api_url') . 'posts.json?' . $this->configuration->get('danbooru_default_request');
    }
}
