<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http;


use function session_unset;

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

    public static function set(string $key, /*mixed*/ $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key) //: mixed
    {
        /* @todo Replace it with a silent log!
        if (!static::has($key)) {
            throw new \Exception(sprintf('Tried to access a not existing session variable %s.', $key));
        }
        */
        return static::has($key) ? $_SESSION[$key] : '';
    }

    public static function delete(string $key): void
    {
        if (static::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(): void
    {
        session_destroy();
        session_unset();
    }
}