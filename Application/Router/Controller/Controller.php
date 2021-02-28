<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller;


abstract class Controller
{
    private array $templateVariables = [];

    protected function assign(string $key, $value): void
    {
        $this->templateVariables[$key] = $value;
    }

    public function getTemplateVariables(): array
    {
        return $this->templateVariables;
    }
}