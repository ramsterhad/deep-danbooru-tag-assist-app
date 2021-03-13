<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config;


use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config\Exception\RouterConfigException;

class Route
{
    private string $alias;

    private string $fullQualifiedNamespacePath;

    private array $methods = [];

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getFullQualifiedNamespacePath(): string
    {
        return $this->fullQualifiedNamespacePath;
    }

    public function setFullQualifiedNamespacePath(string $fullQualifiedNamespacePath): void
    {
        if (!class_exists($fullQualifiedNamespacePath)) {
            throw new RouterConfigException(
                sprintf('Route Config: Class "%s" is not existing.', $fullQualifiedNamespacePath)
            );
        }

        $this->fullQualifiedNamespacePath = $fullQualifiedNamespacePath;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function addMethod(string $method): void
    {
        if (!method_exists($this->fullQualifiedNamespacePath, $method)) {
            throw new RouterConfigException(
                sprintf(
                    'Route Config: method "%s" is not existing for namespace "%s".',
                    $method,
                    $this->fullQualifiedNamespacePath
                )
            );
        }

        $this->methods[] = $method;
    }

    public function hasMethod(string $method): bool
    {
        if (in_array($method, $this->methods)) {
            return true;
        }
        return false;
    }
}