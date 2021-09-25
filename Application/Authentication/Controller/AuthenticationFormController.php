<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

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

    /**
     * Checks if the form to store the credentials was fired.
     *
     * @throws \Exception
     */
    public function checkAuthenticationRequest(): void
    {
        $username = $_POST['username'] ?? '';
        $apiKey = $_POST['api_key'] ?? '';

        try {
            /** @var AuthenticationService $authenticationService */
            $authenticationService = ContainerFactory::getInstance()
                ->getContainer()
                ->get(AuthenticationService::class);

            $authenticationService->authenticate(
                Config::get('danbooru_api_url'),
                $username,
                $apiKey
            );

        } catch (InvalidCredentials $e) {
            Session::set('wrong_credentials', true);
            Router::route('auth');
        }

        Router::route('/');
    }
}
