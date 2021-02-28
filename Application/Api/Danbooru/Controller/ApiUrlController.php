<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class ApiUrlController extends Controller
{
    /**
     * Reset the API URL to the default one
     */
    public function resetApiUrlToDefault(): void
    {
        setcookie('danbooru_api_url', Danbooru::getGetPostUrlFromConfig());
        Router::route('/');
    }

    /**
     * Set the API URL to a custom one, by the input field input_name_danbooru_api_url
     */
    public function setCustomApiUrl(): void
    {
        setcookie('danbooru_api_url', $_POST['input_name_danbooru_api_url']);
        Router::route('/');
    }
}