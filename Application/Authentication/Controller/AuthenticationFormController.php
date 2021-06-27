<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

class AuthenticationFormController implements Controller
{
    public function index(): Response
    {
        $response = new Response($this, 'Authentication.authentication_form.index');

        if (Session::has('wrong_credentials')) {
            Session::delete('wrong_credentials');
            $response->assign('authentication_wrong_credentials', true);
        }

        return $response;
    }

    /**
     * Checks if the form to store the credentials was fired.
     *
     * @throws \Exception
     */
    public function checkAuthenticationRequest(): void
    {
        $username = $_POST['username'] ?? '';
        $key = $_POST['api_key'] ?? '';

        (new Authentication())->authenticate($username, $key);
        Router::route('/');
    }
}