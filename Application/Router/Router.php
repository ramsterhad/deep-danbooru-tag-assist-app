<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config\RouterConfig;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Exception\TemplateNotFoundException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\DefaultController;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

use function sprintf;

final class Router
{
    private static ?self $instance = null;

    private ?RouterConfig $routerConfig = null;

    private string $requestController;

    private Controller $controller;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function hasRoute(string $route): bool
    {
        return $this->getRouterConfig()->hasAlias($route);
    }

    public static function route(string $route): void
    {
        if (!static::$instance->getRouterConfig()->hasAlias($route)) {
            //@todo silent log
            $route = '_default';
        }

        header('location: index.php?' . $route);
        exit;
    }

    /**
     * Checks get parameter "r" (route).
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function processRequest(): void
    {
        $this->readControllerAndActionFromRequest();
        $routerConfig = $this->getRouterConfig();

        // In case the alias is unknown or empty, the default alias will be used.
        if (!$routerConfig->hasAlias($this->requestController) || empty($this->requestController)) {
            //@todo silent log
            //throw new \Exception('has not controller ' . $controllerAlias);
            $this->requestController = $routerConfig->getDefaultRoute()->getAlias();
        }

        $route = $routerConfig->getRouteByAlias($this->requestController);

        $fullQualifiedNamespace = $route->getFullQualifiedNamespacePath();

        $this->controller = ContainerFactory::getInstance()->getContainer()->get($fullQualifiedNamespace);

        if (!($this->controller instanceof Controller)) {
            throw new Exception(
                sprintf('I can\'t work with this thing "%s"!', $fullQualifiedNamespace)
            );
        }

        $action = $routerConfig->getRouteByAlias($this->requestController)->getMethod();

        if (!method_exists($this->controller, $action)) {
            throw new Exception(sprintf('Called unknown method "%s" for object "%s".', $action, $this->controller));
        }

        $response = $this->controller->{$action}();

        if ($response instanceof Response) {
            $this->displayTemplate($response);
        }
    }

    private function displayTemplate(Response $response)
    {
        if (!is_file($response->getFullPathToTemplateFile())) {
            throw new TemplateNotFoundException();
        }

        require_once (new DefaultController())->header()->getFullPathToTemplateFile();

        //$vars = $response->getTemplateVariables(); // Accessible in the templates. // TODO
        require_once $response->getFullPathToTemplateFile();

        require_once (new DefaultController())->footer()->getFullPathToTemplateFile();
    }

    /**
     * Populates the properties requestController and requestAction.
     */
    private function readControllerAndActionFromRequest(): void
    {
        $controllerAlias = array_key_first($_GET) ?? '';

        // If not get, then maybe post?
        if ($controllerAlias === '') {
            $controllerAlias = $_POST['r'] ?? '';
        }

        // Nothing? Call the fallback controller.
        if ($controllerAlias === '') {
            $controllerAlias = '_default';
        }

        $this->requestController = $controllerAlias;
    }

    public function loadRouterConfigObject(): void
    {
        $rc = new RouterConfig();
        $rc->loadConfig();
        $rc->parseConfigFileToRoutes();
        $this->routerConfig = $rc;
    }

    public function getRouterConfig(): RouterConfig
    {
        if (!$this->routerConfig instanceof RouterConfig) {
            $this->loadRouterConfigObject();
        }
        return $this->routerConfig;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }
}
