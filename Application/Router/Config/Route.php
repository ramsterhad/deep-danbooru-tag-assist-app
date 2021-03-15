<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config;


use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config\Exception\RouterConfigException;

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
            throw new RouterConfigException(
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