<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Shared\Exception\ApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Session;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\ErrorLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\RequestLogger;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Shared\Exception\FrameworkException;
use function setcookie;

class Kernel
{
    private static ?self $instance = null;

    private string $error = '';

    private function __construct() {}

    private function __clone() {}

    public static function getInstance(): self
    {
        if (!static::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function startSession(): bool
    {
        return Session::start();
    }

    /**
     * If the user hasn't a session yet, try to auto-authenticate the user.
     */
    public function authenticate(): void
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = ContainerFactory::getInstance()->getContainer()->get(AuthenticationService::class);

        if (!AuthenticationService::isAuthenticated()) {
            $authenticationService->autoAuthentication();
        }
    }

    public function run(): void
    {
        $this->startSession();

        try {
            $this->authenticate();
        } catch (\Exception $e) {
            $this->displayErrorAndExit($e);
        }

        try {
            Router::getInstance()->processRequest();
        } catch (InvalidCredentials $e) {
            Session::destroy();
            Router::route('auth');
        } catch (PostResponseApplicationException|PostResponseApplicationException $e) {

            /** @var ConfigurationInterface $configuration */
            $configuration = ContainerFactory::getInstance()->getContainer()->get(ConfigurationInterface::class);

            if ($configuration->get('debug')) {
                (new RequestLogger())->log($e->getStacktraceWithCode());
            }

            setcookie('danbooru_api_url', '', 0);
            $this->displayErrorAndExit($e);

        // Log always.
        } catch (ApplicationException|FrameworkException $e) {

            (new ErrorLogger())->log($e->getStacktraceWithCode());
            $this->displayErrorAndExit($e);
        }
    }

    public function displayErrorAndExit(\Exception $exception): void
    {
        $str = '<h1>Oops!</h1>';
        $str .= 'A wild error appeared! Please stay calm and go back to the <a href="index.php?_default">start page</a>.<br>';
        $str .= '<br>Further information: <br>';
        $str .= $exception->getMessage();
        echo $str;
        exit;
    }

    /**
     * With trailing separator.
     *
     * @return string
     */
    public static function getBasePath(): string
    {
        return BASE_PATH;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function isAuthenticated(): bool
    {
        return AuthenticationService::isAuthenticated() ?? false;
    }
}
