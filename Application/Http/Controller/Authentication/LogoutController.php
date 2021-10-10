<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Authentication;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;

use function setcookie;

class LogoutController implements ControllerInterface
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function index(): void
    {
        unset($_COOKIE['danbooru_api_url']);
        setcookie('danbooru_api_url', '', time() - 1);

        $this->authenticationService->logout();
        Router::route('/');
    }
}
