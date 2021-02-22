<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Api\MachineLearningPlatform\MachineLearningPlatform;
use Ramsterhad\DeepDanbooruTagAssist\Api\Tag\Collection;
use Ramsterhad\DeepDanbooruTagAssist\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Configuration\Config;


class Application
{
    private static ?self $instance = null;

    private string $error = '';

    private Collection $unknownTags;

    private Danbooru $danbooru;

    private MachineLearningPlatform $machineLearningPlatform;

    private bool $isLoggedIn = false;

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
        return session_start();
    }

    public function run(): void
    {
        $this->executeSystemRequirementChecks();

        $this->startSession();

        try {
            // Check if a form was submitted
            $this->checkLoginRequest();

            $this->login();

            $this->checkResetRequest();
            $this->checkSetApiUrlRequest();
            $this->checkAddNewTags();

            // Build the page
            $this->danbooru = new Danbooru(Danbooru::loadEndpointAddress());
            $this->danbooru->callForTags();

            $this->machineLearningPlatform = new MachineLearningPlatform();
            $this->machineLearningPlatform->setPicture(new Picture($this->danbooru->getPost()->getPicOriginal()));
            $this->machineLearningPlatform->callForTags();

            // List the tags from Danbooru, the ML Platform and the difference between them
            // The unknown tags are later listed and registered with the numpad keys.
            $this->unknownTags = $this->machineLearningPlatform->filterTagsFromMlpAgainstAlreadyKnownTags(
                $this->danbooru->getPost()->getTagCollection()
            );

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
     * Checks if the form to store the credentials was fired. It actually doesn't log in the user, yet. This happens in
     * \Ramsterhad\DeepDanbooruTagAssist\Application\Application::login.
     *
     * @throws \Exception
     */
    public function checkLoginRequest(): void
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'] ?? '';
            $apiKey = $_POST['api_key'] ?? '';

            $danbooru = new Danbooru(Config::get('danbooru_api_url'));
            if ($danbooru->login($username, $apiKey)) {
                $_SESSION['username'] = $username;
                $_SESSION['api_key'] = $apiKey;
                Router::route('/');
            }
        }
    }

    /**
     * Searches for session variables username and api_key. If they are existing then the config variables
     * danbooru_user and danbooru_pass are overwritten by them. Which has an effect to the api call and which basically
     * logs the user in.
     * The session variables are only set in \Ramsterhad\DeepDanbooruTagAssist\Application\Application::checkLoginRequest.
     * That means: When they are set, then the login was successfully.
     *
     * When the log in with the session variables wasn't successful, then try to log in with the config variables.
     */
    public function login(): void
    {
        if (isset($_SESSION['username']) && isset($_SESSION['api_key'])) {
            Config::set('danbooru_user', $_SESSION['username']);
            Config::set('danbooru_pass', $_SESSION['api_key']);
            $this->isLoggedIn = true;
        }

        // if not logged in yet, try to log in with the config variables, if they were set.
        if (!$this->isLoggedIn && !empty(Config::get('danbooru_user')) && !empty(Config::get('danbooru_pass'))) {

            $danbooru = new Danbooru(Config::get('danbooru_api_url'));
            if ($danbooru->login(Config::get('danbooru_user'), Config::get('danbooru_pass'))) {
                $_SESSION['username'] = Config::get('danbooru_user');
                $_SESSION['api_key'] = Config::get('danbooru_pass');
                Router::route('/');
            }
        }
    }

    /**
     * Reset the API URL to the default one
     */
    public function checkResetRequest(): void
    {
        if (isset($_POST['reset_danbooru_api_url'])) {
            setcookie('danbooru_api_url', Danbooru::getGetPostUrlFromConfig());
            Router::route('/');
        }
    }

    /**
     * Set the API URL to a custom one, by the input field input_name_danbooru_api_url
     */
    public function checkSetApiUrlRequest(): void
    {
        if (isset($_POST['set_danbooru_api_url']) && isset($_POST['input_name_danbooru_api_url'])) {
            setcookie('danbooru_api_url', $_POST['input_name_danbooru_api_url']);
            Router::route('/');
        }
    }

    public function checkAddNewTags(): void
    {
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

    public function getDanbooru(): Danbooru
    {
        return $this->danbooru;
    }

    public function getMachineLearningPlatform(): MachineLearningPlatform
    {
        return $this->machineLearningPlatform;
    }

    public function getUnknownTags(): Collection
    {
        return $this->unknownTags;
    }

    /**
     * Returns the count of \Ramsterhad\DeepDanbooruTagAssist\Application\Application::$unknownTags but limits the
     * max number by 9.
     * The information is used for the frontend to build a matrix.
     *
     * @return int
     */
    public function getCountedUnknownTagsLimitedByValue(int $limit = 15): int
    {
        $maxTags = count($this->getUnknownTags());
        return $maxTags > $limit ? $limit : $maxTags;
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }
}