<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\ErrorLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\Logger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\RequestLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;


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
        if (!Authentication::isAuthenticated()) {
            (new Authentication())->autoAuthentication();
        }
    }

    public function run(): void
    {
        $this->executeSystemRequirementChecks();

        $this->startSession();

        $this->authenticate();

        try {
            Router::getInstance()->processRequest();
        } catch (\Exception $ex) {

            $error = sprintf('code: %d%s%s', $ex->getCode(), \PHP_EOL, $ex->getTraceAsString());

            $logger = new ErrorLogger();
            $logger->log($error);

            $this->displayErrorAndExit($ex);
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
        return Authentication::isAuthenticated() ?? false;
    }
}