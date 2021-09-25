<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

class AuthenticationController implements Controller
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
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
                Config::get('danbooru_api_url'),
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
