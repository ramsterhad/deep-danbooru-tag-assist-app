<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\MachineLearningPlatform;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Collection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Form\LoginForm;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;


class Application
{
    private static ?self $instance = null;

    private string $error = '';

    private Controller $controller;

    private array $templateVariables = [];

    private Authentication $authentication;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance(): self
    {
        if (!static::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function startSession(): bool
    {
        return Session::start();
    }

    public function authenticate(): void
    {
        $this->authentication = new Authentication();

        // Check if the login form was submitted
        /*
        $loginForm = new LoginForm();
        $loginForm->checkAuthenticationRequest();
        */

        // If the user hasn't a session yet, try to auto-authenticate the user.
        if (!Authentication::isAuthenticated()) {
            $this->authentication->autoAuthentication();
        }
    }

    public function run(): void
    {
        $this->executeSystemRequirementChecks();

        $this->startSession();

        $this->authenticate();

        try {



            Router::getInstance()->processRequest();
            $this->controller = Router::getInstance()->getController();
            $this->templateVariables = $this->controller->getTemplateVariables();

        } catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }

    /**
     * Check if the tmp directory does exist and is writeable.
     *
     * @throws \Exception
     */
    public function executeSystemRequirementChecks()
    {
        $systemRequirements = new SystemRequirements();
        $systemRequirements->checkRequirementsForPictureHandling();
    }

    /**
     * Reset the API URL to the default one
     */
    public function checkResetRequest(): void
    {   exit('checkResetRequest');
        if (isset($_POST['reset_danbooru_api_url'])) {
            setcookie('danbooru_api_url', Danbooru::getGetPostUrlFromConfig());
            Router::route('/');
        }
    }

    /**
     * Set the API URL to a custom one, by the input field input_name_danbooru_api_url
     */
    public function checkSetApiUrlRequest(): void
    {   exit('checkSetApiUrlRequest');
        if (isset($_POST['set_danbooru_api_url']) && isset($_POST['input_name_danbooru_api_url'])) {
            setcookie('danbooru_api_url', $_POST['input_name_danbooru_api_url']);
            Router::route('/');
        }
    }

    public function checkAddNewTags(): void
    {exit('checkAddNewTags');
        if (isset($_POST['name_tag_checkbox_submit']) && isset($_POST['tag_checkbox'])) {

            $id = (int) $_POST['tag_checkbox_post_id'];
            $collection = new Collection();

            foreach ($_POST['tag_checkbox_existing_tags'] as $tag) {
                $collection->add(new Tag($tag, '0.0'));
            }

            foreach ($_POST['tag_checkbox'] as $tag) {
                $collection->add(new Tag($tag, '0.0'));
            }

            $danbooru = new Danbooru('');
            $danbooru->pushTags($id, $collection);

            Router::route('/');
        }
    }

    public static function getBasePath(): string
    {
        return BASE_PATH;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function get(string $key)
    {
        //@todo error handling
        return $this->templateVariables[$key];
    }

    /**
     * Returns the count of \Ramsterhad\DeepDanbooruTagAssist\Application\Application::$unknownTags but limits the
     * max number by 9.
     * The information is used for the frontend to build a matrix.
     *
     * @param int $limit
     * @return int
     */
    public function getCountedUnknownTagsLimitedByValue(int $limit = 15): int
    {
        $maxTags = count($this->get('unknownTags'));
        return $maxTags > $limit ? $limit : $maxTags;
    }

    public function isAuthenticated(): bool
    {
        return Authentication::isAuthenticated() ?? false;
    }
}