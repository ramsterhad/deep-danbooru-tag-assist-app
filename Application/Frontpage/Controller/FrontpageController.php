<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Frontpage\Controller;



use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Endpoint;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\MachineLearningPlatform;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\PredictedTagsDatabase;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;


class FrontpageController implements Controller
{
    public function index(): Response
    {
        if (!Authentication::isAuthenticated()) {
            Router::route('auth');
        }

        // Build the page
        $danbooru = new Danbooru();
        $danbooru->requestTags(
            new TagCollection(),
            new Post(),
            new Endpoint()
        );

        $machineLearningPlatform = new MachineLearningPlatform();
        $machineLearningPlatform->setPicture(new Picture($danbooru->getPost()->getPicOriginal()));
        $machineLearningPlatform->requestTags();

        // List the tags from Danbooru, the ML Platform and the difference between them
        // The unknown tags are later listed and registered with the numpad keys.
        $unknownTags = $danbooru->filterTagsAgainstAlreadyKnownTags($machineLearningPlatform->getCollection());

        $response = new Response($this, 'Frontpage.frontpage.index');
        $response->assign('danbooru', $danbooru);
        $response->assign('machineLearningPlatform', $machineLearningPlatform);
        $response->assign('unknownTags', $unknownTags);
        $response->assign('dannboruApiUrl', Config::get('danbooru_api_url'));
        $response->assign('suggestedTagsLimit', (int) Config::get('limit_for_suggested_tags'));

        return $response;
    }

    /**
     * Returns the count of \Ramsterhad\DeepDanbooruTagAssist\Application\Application::$unknownTags but limits the
     * max number by 9.
     * The information is used for the frontend to build a matrix.
     *
     * @param TagCollection $unknownTags
     * @param int $limit
     * @return int
     */
    public function getCountedUnknownTagsLimitedByValue(TagCollection $unknownTags, int $limit = 15): int
    {
        $maxTags = count($unknownTags);
        return $maxTags > $limit ? $limit : $maxTags;
    }
}
