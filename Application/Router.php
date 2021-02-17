<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


class Router
{
    private static ?self $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function route(string $route): void
    {
        $routes = [
            '/' => 'index.php',
        ];

        if (!array_key_exists($route, $routes)) {
            throw new \Exception(sprintf('Route "%s" does not exist!'. $route));
        }

        header('location: ' . $routes[$route]);
        exit;
    }
}
