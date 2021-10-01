<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Exception\ApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\ErrorLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\RequestLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;


class Application
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
        $this->executeSystemRequirementChecks();

        $this->startSession();

        $this->authenticate();

        try {
            Router::getInstance()->processRequest();

        } catch (PostResponseApplicationException $e) {
            if (Config::get('debug')) {
                (new RequestLogger())->log($e->getStacktraceWithCode());
            }

            \setcookie('danbooru_api_url', '', 0);
            $this->displayErrorAndExit($e);

        // Log always.
        } catch (ApplicationException $e) {

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
     * Check if the tmp directory does exist and is writeable.
     *
     * @throws \Exception
     */
    public function executeSystemRequirementChecks()
    {
        $systemRequirements = new SystemRequirements();
        $systemRequirements->checkRequirementsForPictureHandling();
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