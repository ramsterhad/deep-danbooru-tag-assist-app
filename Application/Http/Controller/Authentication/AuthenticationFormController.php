<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Authentication;

use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\DataType\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Session;

class AuthenticationFormController implements ControllerInterface
{
    public function index(): Response
    {
        $response = new Response($this, '.authentication_form.index');

        $response->assign('showManual', false);

        if (Session::has('wrong_credentials')) {
            Session::delete('wrong_credentials');
            $response->assign('authentication_wrong_credentials', true);
        }

        return $response;
    }
}
