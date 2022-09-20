<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;

class Repository
{
    public function has(string $key): bool
    {
        return isset($_ENV[$key]);
    }

    /**
     * If the value is a boolean string ("true" or "false"), then it will return it as a boolean.
     *
     * @throws ParameterNotFoundException
     */
    public function get(string $key): string|bool
    {
        if (!self::has($key)) {
            throw new ParameterNotFoundException($key);
        }

        return $_ENV[$key];
    }

    public function set(string $key, string $value): void
    {
        $_ENV[$key] = $value;
    }
}
