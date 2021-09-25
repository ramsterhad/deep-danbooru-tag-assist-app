<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;

use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

class AuthenticationFormController implements Controller
{
    public function index(): Response
    {
        $response = new Response($this, 'Authentication.authentication_form.index');

        $response->assign('showManual', false);

        if (Session::has('wrong_credentials')) {
            Session::delete('wrong_credentials');
            $response->assign('authentication_wrong_credentials', true);
        }

        return $response;
    }
}
