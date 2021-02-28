<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;


class Application
{
    private static ?self $instance = null;

    private string $error = '';

    private Controller $controller;

    private array $templateVariables = [];


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
            $this->controller = Router::getInstance()->getController();
            $this->templateVariables = $this->controller->getTemplateVariables();

        } catch (\Exception $ex) {
            $this->error = $ex->getMessage();
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

    public static function getBasePath(): string
    {
        return BASE_PATH;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function get(string $key)
    {
        //@todo error handling
        return $this->templateVariables[$key];
    }

    public function isAuthenticated(): bool
    {
        return Authentication::isAuthenticated() ?? false;
    }
}