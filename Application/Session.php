<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


class Session
{
    private function __construct() {}
    private function __clone() {}

    public static function start(): bool
    {
        return session_start();
    }

    public static function has(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public static function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): string
    {
        /* @todo Replace it with a silent log!
        if (!static::has($key)) {
            throw new \Exception(sprintf('Tried to access a not existing session variable %s.', $key));
        }
        */
        return static::has($key) ? $_SESSION[$key] : '';
    }

    public static function destroy(): void
    {
        session_destroy();
        unset($_SESSION);
    }
}