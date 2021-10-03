<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service;

use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Infrastructure\Repository;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Exception\ParameterNotFoundException;

class ConfigurationService implements ConfigurationInterface
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function has(string $key): bool
    {
        return $this->repository->has($key);
    }

    /**
     * If the value is a boolean string ("true" or "false"), then it will return it as a boolean.
     *
     * @throws ParameterNotFoundException
     */
    public function get(string $key): string|bool
    {
        if (!$this->has($key)) {
            throw new ParameterNotFoundException($key);
        }

        $variable = $this->repository->get($key);

        if ($variable === 'true') {
            return true;
        }

        if ($variable === 'false') {
            return false;
        }

        return $variable;
    }

    public function set(string $key, string $value): void
    {
        $this->repository->set($key, $value);
    }
}
