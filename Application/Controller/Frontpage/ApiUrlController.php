<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Controller\Frontpage;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\EndpointUrlService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

use function setcookie;

class ApiUrlController implements ControllerInterface
{
    private EndpointUrlService $endpointService;

    public function __construct(EndpointUrlService $endpointService)
    {
        $this->endpointService = $endpointService;
    }

    /**
     * Reset the API URL to the default one
     */
    public function resetApiUrlToDefault(): void
    {

        setcookie('danbooru_api_url', $this->endpointService->getGetPostUrlFromConfig());
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
