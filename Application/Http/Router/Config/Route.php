<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Config;


use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Config\Exception\RouterConfigApplicationException;

class Route
{
    private string $alias;

    private string $fullQualifiedNamespacePath;

    private string $method;

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
            throw new RouterConfigApplicationException(
                sprintf('Route Config: Class "%s" is not existing.', $fullQualifiedNamespacePath)
            );
        }

        $this->fullQualifiedNamespacePath = $fullQualifiedNamespacePath;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}