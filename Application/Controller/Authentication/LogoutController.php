<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Controller\Authentication;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class LogoutController implements ControllerInterface
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function index(): void
    {
        $this->authenticationService->logout();
        Router::route('/');
    }
}
