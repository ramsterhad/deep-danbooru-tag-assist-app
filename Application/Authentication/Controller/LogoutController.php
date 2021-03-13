<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class LogoutController extends Controller
{
    public function index(): void
    {
        (new Authentication())->logout();
        Router::route('/');
    }
}