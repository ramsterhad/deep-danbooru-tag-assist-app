<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AuthenticationError;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Utility\Json;

use function json_decode;
use function property_exists;

final class AuthenticationService
{
    private DanbooruService $danbooruService;

    public function __construct(DanbooruService $danbooruService)
    {
        $this->danbooruService = $danbooruService;
    }

    /**
     * Tries to authenticate the user by an API call addressed to the given URL from the config variable danbooru_api_url.
     * To validate the response it will be checked if there's a valid JSON and if the response contains the
     * property 'id'. If the authentication was successful, then the username and API key is stored in the PHP session,
     * so every request can be authorized. Else an AuthenticationError exception will be thrown.
     *
     * @throws InvalidCredentials|AuthenticationError
     */
    public function authenticate(string $url, string $username, string $apiKey): void
    {
        $response = $this->danbooruService->authenticate($url, $username, $apiKey);

        // No json as return value. This is bad.
        if (!Json::isJson($response)) {
            throw new AuthenticationError(
                'The authentication service didn\'t return a nice response. -_-\'',
                AuthenticationError::CODE_RESPONSE_CONTAINED_INVALID_JSON
            );
        }

        $response = json_decode($response);

        // Json didn't have the id property which every logged in user must have.
        if (!property_exists($response, 'id')) {
            throw new InvalidCredentials(
                'Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.',
                AuthenticationError::CODE_RESPONSE_MISSING_PROPERTIES
            );
        }

        $this->writeUserCredentialsToSession($username, $apiKey);
        $this->setIsAuthenticatedFlag();
    }

    /**
     * Tries to authenticate with the session variables username and api_key.
     * Originally the session variables can be populated by the login form
     * \Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form\Login or by the config variables danbooru_user
     * and danbooru_pass.
     *
     * @throws InvalidCredentials|AuthenticationError|Exception
     */
    public function authenticateBySession(): void
    {
        $this->authenticate(
            Config::get('danbooru_api_url'),
            Session::get('username'),
            Session::get('api_key')
        );
    }

    /**
     * Tries to authenticate with the config variables danbooru_user and danbooru_pass.
     *
     * @throws InvalidCredentials|AuthenticationError|Exception
     */
    public function authenticateByConfig(): void
    {
        $this->authenticate(
            Config::get('danbooru_api_url'),
            Config::get('danbooru_user'),
            Config::get('danbooru_pass')
        );
    }

    /**
     * First attempt to auto login the user is by using the session variables, which can be either populated by the
     * login form \Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form\Login or the config variables
     * danbooru_user & danbooru_pass.
     * When the login with the session variables wasn't successful, then it tries to authenticate with the config variables.
     *
     * it catches the \Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Exception\AuthenticationError Exception, since
     * it wasn't an active request by the user, but a helper function. When the user tries to authenticate and an error
     * appears, then - of course - the message must be forwarded.
     * @throws Exception
     */
    public function autoAuthentication()
    {
        try {
            // Try to login by session.
            $this->authenticateBySession();
        } catch (AuthenticationError $ex) {

        }

        // Still not logged in? Try it with the credentials from the config file.
        if (!AuthenticationService::isAuthenticated()) {
            try {
                $this->authenticateByConfig();
            } catch (AuthenticationError $ex) {

            }
        }
    }

    public static function isAuthenticated(): bool
    {
        return (bool) Session::get('authenticated');
    }

    public function writeUserCredentialsToSession(string $username, string $apiKey): void
    {
        Session::set('username', $username);
        Session::set('api_key', $apiKey);
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
