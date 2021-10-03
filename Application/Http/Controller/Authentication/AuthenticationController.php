<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Authentication;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Session;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;


class AuthenticationController implements ControllerInterface
{
    private AuthenticationService $authenticationService;
    private ConfigurationInterface $configuration;

    public function __construct(
        AuthenticationService $authenticationService,
        ConfigurationInterface $configuration,
    ) {
        $this->configuration = $configuration;
        $this->authenticationService = $authenticationService;
    }

    /**
     * Checks if the form to store the credentials was fired.
     *
     * @throws \Exception
     */
    public function checkAuthenticationRequest(): void
    {
        $username = $_POST['username'] ?? '';
        $apiKey = $_POST['api_key'] ?? '';

        try {
            $this->authenticationService->authenticate(
                $this->configuration->get('danbooru_api_url'),
                $username,
                $apiKey
            );

        } catch (InvalidCredentials $e) {
            Session::set('wrong_credentials', true);
            Router::route('auth');
        }

        Router::route('/');
    }
}
