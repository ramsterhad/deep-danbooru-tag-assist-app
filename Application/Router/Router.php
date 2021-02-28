<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router;


use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config\RouterConfig;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;


final class Router
{
    private static ?self $instance = null;

    private array $routes = [
        '/' => 'index.php',
    ];

    private string $requestController;
    private string $requestAction;

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
        if (array_key_exists($route, $this->routes)) {
            return true;
        }
        return false;
    }

    public static function route(string $route): void
    {
        if (!static::$instance->hasRoute($route)) {
            //@todo silent log
            $route = '/';
        }

        header('location: ' . static::$instance->routes[$route]);
        exit;
    }

    /**
     * Checks get parameter "r" (route).
     */
    public function processRequest(): void
    {
        $this->readControllerAndActionFromRequest();
        $routerConfig = $this->loadRouterConfigObject();

        // In case the alias is unknown or empty, the default alias will be used.
        if (!$routerConfig->hasAlias($this->requestController) || empty($this->requestController)) {
            //@todo silent log
            //throw new \Exception('has not controller ' . $controllerAlias);
            $this->requestController = $routerConfig->getDefaultRoute()->getAlias();
        }

        $route = $routerConfig->getRouteByAlias($this->requestController);

        // In case the method is unknown, then the default route is used.
        if (!$route->hasMethod($this->requestAction)) {
            $route = $routerConfig->getDefaultRoute();
            $this->requestAction = $route->getMethods()[0];
        }

        $controllerFullQualifiedNamespacePath = $route->getFullQualifiedNamespacePath();
        $this->controller = new $controllerFullQualifiedNamespacePath();

        if (!($this->controller instanceof Controller)) {
            throw new \Exception(
                sprintf('I can\'t work with this thing "%s"!', $controllerFullQualifiedNamespacePath)
            );
        }

        $this->controller->{$this->requestAction}();
    }

    /**
     * Populates the properties requestController and requestAction.
     */
    private function readControllerAndActionFromRequest(): void
    {
        $controllerAlias = $_GET['c'] ?? '';
        $action = $_GET['a'] ?? '';

        // Maybe get or maybe post?
        if ($controllerAlias === '') {
            $controllerAlias = $_POST['c'] ?? '';
            $action = $_POST['a'] ?? '';
        }

        $this->requestController = $controllerAlias;
        $this->requestAction = $action;
    }

    private function loadRouterConfigObject(): RouterConfig
    {
        $rc = new RouterConfig();
        $rc->loadConfig();
        $rc->parseConfigFileToRoutes();
        return $rc;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }
}
