<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\DotEnv;


use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\ConfigContract;

class Config implements ConfigContract
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}

    public static function get(string $name): string
    {
        if (!self::getInstance()->has($name)) {
            throw new \Exception(sprintf('Config parameter %s is unknown!', $name));
        }

        return $_ENV[$name];
    }

    public function has(string $name): bool
    {
        return isset($_ENV[$name]);
    }
}