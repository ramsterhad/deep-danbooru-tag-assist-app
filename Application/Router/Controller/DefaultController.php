<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;

class DefaultController implements Controller
{
    public function header(): Response
    {
        return new Response($this, 'Router.default.header');
    }

    public function footer(): Response
    {
        return new Response($this, 'Router.default.footer');
    }
}