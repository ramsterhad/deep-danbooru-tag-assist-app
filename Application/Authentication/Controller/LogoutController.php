<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

class LogoutController implements Controller
{
    public function index(): void
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = ContainerFactory::getInstance()->getContainer()->get(AuthenticationService::class);
        $authenticationService->logout();

        Router::route('/');
    }
}