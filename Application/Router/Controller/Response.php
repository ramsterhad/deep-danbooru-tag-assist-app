<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Application;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;

class Response
{
    private Controller $controller;
    private string $templateName;

    private array $templateVariables = [];

    /**
     * The template name is an information build from the domain directory, the controllers name and the template file.
     * Example:     Application/Frontpage/Controller/WelcomeController (and the action method index())
     * Becomes to:  new Response($this, 'Frontpage.welcome.index');
     * Which loads: Application/Frontpage/templates/welcome/index.tpl.php
     *
     * @param Controller $controller
     * @param string $templateName
     */
    public function __construct(Controller $controller, string $templateName)
    {
        $this->controller = $controller;
        $this->templateName = $templateName;
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function assign(string $key, $value): Response
    {
        $this->templateVariables[$key] = $value;
        return $this;
    }

    public function has(string $key): bool
    {
        if (isset($this->templateVariables[$key])) {
            return true;
        }
        return false;
    }

    public function get(string $key) //: mixed
    {
        if ($this->has($key)) {
            return $this->templateVariables[$key];
        }

        throw new \Exception('no tpl var for key '. $key); // @todo
    }

    /**
     * Transforms Response template declaration into an real existing path.
     * @return string
     */
    public function getFullPathToTemplateFile(): string
    {
        // Add the template directory.
        $fullTemplatePath = preg_replace('/\./', '.templates.', $this->templateName, 1);
        // Replace all dots by directory separators.
        $fullTemplatePath = str_replace('.', DIRECTORY_SEPARATOR, $fullTemplatePath);
        // Add file extension.
        $fullTemplatePath .= '.tpl.php';

        return Application::getBasePath() . 'Application' . DIRECTORY_SEPARATOR . $fullTemplatePath;
    }
}
