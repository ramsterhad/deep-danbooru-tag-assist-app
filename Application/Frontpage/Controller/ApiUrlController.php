<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Frontpage\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\EndpointUrlService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

use function setcookie;

class ApiUrlController implements Controller
{
    /**
     * Reset the API URL to the default one
     */
    public function resetApiUrlToDefault(): void
    {
        /** @var EndpointUrlService $endpointService */
        $endpointService = ContainerFactory::getInstance()->getContainer()->get(EndpointUrlService::class);

        setcookie('danbooru_api_url', $endpointService->getGetPostUrlFromConfig());
        Router::route('/');
    }

    /**
     * Set the API URL to a custom one, by the input field input_name_danbooru_api_url
     */
    public function setCustomApiUrl(): void
    {
        setcookie('danbooru_api_url', $_POST['input_name_danbooru_api_url']);
        Router::route('/');
    }
}
