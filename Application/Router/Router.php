<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller\ApiUrlController;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller\TagsController;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller\AuthenticationFormController;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Controller\LogoutController;
use Ramsterhad\DeepDanbooruTagAssist\Application\Frontpage\Controller\FrontpageController;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;


final class Router
{
    private static ?self $instance = null;

    private array $routes = [
        '/' => 'index.php',
    ];

    private array $controllerMap = [
        ''              => FrontpageController::class,
        'auth'          => AuthenticationFormController::class,
        'logout'        => LogoutController::class,
        'apiurl'        => ApiUrlController::class,
        'pushnewtags'   => TagsController::class
    ];

    private array $actions = [
        '' => [
            'index',
        ],
        'auth' => [
            'checkAuthenticationRequest',
        ],
        'logout' => [
            'index',
        ],
        'apiurl' => [
            'resetApiUrlToDefault',
            'setCustomApiUrl',
        ],
        'pushnewtags' => [
            'pushNewTagsToDanbooru',
        ]
    ];

    private $defaultControllerAlias = '';
    private $defaultControllerMethod = 'index';

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

    public function hasController(string $controllerKey): bool
    {
        if (array_key_exists($controllerKey, $this->actions)) {
            return true;
        }
        return false;
    }

    public function hasControllerAction(string $controllerKey, string $action): bool
    {
        if (array_key_exists($controllerKey, $this->actions)) {

            if (in_array($action, static::$instance->actions[$controllerKey])) {
                return true;
            }
        }
        return false;
    }

    public function getFullQualifiedNamespaceByAlias(string $alias): string
    {
        if (!array_key_exists($alias, $this->controllerMap)) {
            throw new \Exception(sprintf('Unknown Alias "%s"', $alias));
        }

        return static::$instance->controllerMap[$alias];
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
        $controllerAlias = $_GET['c'] ?? '';
        $action = $_GET['a'] ?? '';

        // Maybe get or maybe post?
        if ($controllerAlias === '') {
            $controllerAlias = $_POST['c'] ?? '';
            $action = $_POST['a'] ?? '';
        }

        if (!$this->hasController($controllerAlias)) {
            //@todo silent log
            throw new \Exception('has not controller ' . $controllerAlias);
        }

        if (!$this->hasControllerAction($controllerAlias, $action)) {
            //@todo silent log
            $controllerAlias = $this->defaultControllerAlias;
            $action = $this->defaultControllerMethod;
        }

        $controllerNamespace = $this->getFullQualifiedNamespaceByAlias($controllerAlias);

        $controller = new $controllerNamespace;

        if (!($controller instanceof Controller)) {
            throw new \Exception(sprintf('I can\'t work with this thing "%s"!', $controllerAlias));
        }

        if (!method_exists($controller, $action)) {
            throw new \Exception(sprintf('Unknown method "%s" for controller "%s"', $action, $controller));
        }

        $controller->$action();
        $this->controller = $controller;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }
}
