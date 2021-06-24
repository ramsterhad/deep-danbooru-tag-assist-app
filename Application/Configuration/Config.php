<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Configuration;


class Config
{
    private static ?self $instance = null;

    private ConfigContract $config;

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self(DotEnv\Config::getInstance());
        }
        return self::$instance;
    }

    private function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }
    private function __clone() {}

    /**
     * If a boolean is recognised the method returns the boolean, otherwise a string.
     *
     * @param string $name
     * @return string|bool
     * @throws \Exception
     */
    public static function get(string $name) /*string|bool*/
    {
        if (!self::has($name)) {
            throw new \Exception(sprintf('Config parameter %s is unknown!', $name));
        }

        $variable = $_ENV[$name];

        if ($variable === 'true') {
            return true;
        }

        if ($variable === 'false') {
            return false;
        }

        return $variable;
    }

    public static function has(string $name): bool
    {
        return isset($_ENV[$name]);
    }

    public static function set(string $key, string $value): void
    {
        $_ENV[$key] = $value;
    }
}