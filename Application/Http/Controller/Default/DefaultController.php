<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Default;

use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\DataType\Response;

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