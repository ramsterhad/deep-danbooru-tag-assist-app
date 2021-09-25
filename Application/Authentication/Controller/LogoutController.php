<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class LogoutController implements Controller
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
