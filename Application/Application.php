<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
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
            $this->error = $ex->getMessage();
        print_r($this->error);
        }
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