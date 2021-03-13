<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AuthenticationError;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\DanbooruApiBridge\DanbooruApiBridge;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

final class Authentication
{
    /**
     * Tries to authenticate with the session variables username and api_key.
     * Originally the session variables can be populated by the login form
     * \Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form\Login or by the config variables danbooru_user
     * and danbooru_pass.
     */
    public function authenticateBySession(): void
    {
        $this->authenticate(Session::get('username'), Session::get('api_key'));
    }

    /**
     * Tries to authenticate with the config variables danbooru_user and danbooru_pass.
     */
    public function authenticateByConfig(): void
    {
        $this->authenticate(Config::get('danbooru_user'), Config::get('danbooru_pass'));
    }

    /**
     * First attempt to auto login the user is by using the session variables, which can be either populated by the
     * login form \Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form\Login or the config variables
     * danbooru_user & danbooru_pass.
     * When the login with the session variables wasn't successful, then it tries to authenticate with the config variables.
     *
     * it catches the \Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Exception\AuthenticationError Exception, since
     * it wasn't an active request by the user, but a helper function. When the user tries to authenticate it an error
     * appears, then - of course - the message must be forwarded.
     */
    public function autoAuthentication()
    {
        try {
            // Try to login by session.
            $this->authenticateBySession();
        } catch (AuthenticationError $ex) {

        }

        // Still not logged in? Try it with the credentials from the config file.
        if (!static::isAuthenticated()) {
            try {
                $this->authenticateByConfig();
            } catch (AuthenticationError $ex) {

            }
        }
    }

    /**
     * Tries to authenticate the user by an API call addressed to the given URL from the config variable danbooru_api_url.
     * If the authentication was successful, then the username and API key is stored in the PHP session.
     *
     * @param string $username
     * @param string $key
     * @return bool
     */
    public function authenticate(string $username, string $key): bool
    {
        $danbooruBridge = new DanbooruApiBridge();
        if ($danbooruBridge->authenticate($username, $key)) {
            $this->writeUserCredentialsToSession($username, $key);
            $this->setIsAuthenticatedFlag();
            return true;
        }
        return false;
    }

    public function writeUserCredentialsToSession(string $username, string $key): void
    {
        Session::set('username', $username);
        Session::set('api_key', $key);
    }

    public static function isAuthenticated(): bool
    {
        return (bool) Session::get('authenticated');
    }

    public function setIsAuthenticatedFlag(): void
    {
        Session::set('authenticated', true);
    }

    public function logout()
    {
        Session::destroy();
    }
}