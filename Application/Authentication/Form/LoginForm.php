<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router;

class LoginForm
{
    /**
     * Checks if the form to store the credentials was fired.
     *
     * @throws \Exception
     */
    public function checkAuthenticationRequest(Authentication $authentication): void
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'] ?? '';
            $key = $_POST['api_key'] ?? '';

            $authentication->authenticate($username, $key);
            Router::route('/');
        }
    }
}