<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Controller\Default;

use Ramsterhad\DeepDanbooruTagAssist\Application\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\DataType\Response;

class DefaultController implements ControllerInterface
{
    public function header(): Response
    {
        return new Response($this, '.header');
    }

    public function footer(): Response
    {
        return new Response($this, '.footer');
    }
}