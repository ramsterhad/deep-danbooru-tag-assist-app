<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;

interface ConfigurationInterface
{
    public function has(string $key): bool;

    /**
     * If the value is recognised as a boolean, then the method returns it as a boolean, otherwise as a string.
     *
     * @throws ParameterNotFoundException
     */
    public function get(string $key): string|bool;

    public function set(string $key, string $value): void;
}
