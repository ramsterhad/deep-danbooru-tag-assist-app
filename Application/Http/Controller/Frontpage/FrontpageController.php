<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage;

use Exception;
use JsonException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\AuthenticationService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\FilterAlreadyKnownTags;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Filter\FilterSafeTagsExcludeListThresholdService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture\DeletePictureService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\EndpointUrlService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\RequestPostService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\MachineLearningPlatform;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\DatabaseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\PredictedTagsDatabaseInvalidResponseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\PredictedTagsDatabase;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\DataType\Response;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;

class FrontpageController implements ControllerInterface
{
    private ConfigurationInterface $configuration;
    private DeletePictureService $deletePictureService;
    private EndpointUrlService $endpointUrlService;
    private FilterAlreadyKnownTags $filterAlreadyKnownTags;
    private FilterSafeTagsExcludeListThresholdService $filterSafeTagsExcludeListThresholdService;
    private RequestPostService $requestPostService;

    public function __construct(
        ConfigurationInterface $configuration,
        DeletePictureService $deletePictureService,
        EndpointUrlService $endpointUrlService,
        FilterAlreadyKnownTags $filterAlreadyKnownTags,
        FilterSafeTagsExcludeListThresholdService $filterSafeTagsExcludeListThresholdService,
        RequestPostService $requestPostService,
    ) {
        $this->configuration = $configuration;
        $this->deletePictureService = $deletePictureService;
        $this->requestPostService = $requestPostService;
        $this->filterAlreadyKnownTags = $filterAlreadyKnownTags;
        $this->filterSafeTagsExcludeListThresholdService = $filterSafeTagsExcludeListThresholdService;
        $this->endpointUrlService = $endpointUrlService;
    }

    /**
     * @throws PostResponseApplicationException
     * @throws InvalidCredentials
     * @throws JsonException
     * @throws Exception
     */
    public function index(): Response
    {
        if (!AuthenticationService::isAuthenticated()) {
            Router::route('auth');
        }

        $post = $this->requestPostService->request();

        // Try to use pre predicted tags. If something fails with the database, use the machine learning platform api.
        try {
            $predictedTagsDatabase = new PredictedTagsDatabase();
            $predictedTagsDatabase->requestTags($post->getId());
            $suggestedTags = $predictedTagsDatabase->getCollection();

        } catch (DatabaseException|PredictedTagsDatabaseInvalidResponseException $ex) {

            $machineLearningPlatform = new MachineLearningPlatform();
            $machineLearningPlatform->setPicture($post->getPicture());
            $machineLearningPlatform->requestTags();
            $suggestedTags = $machineLearningPlatform->getCollection();

        } finally {
            $this->deletePictureService->delete(
                $post->getPicture()
            );
        }

        $suggestedTags = $this->filterSafeTagsExcludeListThresholdService->filter($suggestedTags, $post);
        $unknownTags = $this->filterAlreadyKnownTags->filter($suggestedTags, $post);

        $response = new Response($this, '.frontpage.index');
        $response->assign('post', $post);
        $response->assign('suggestedTags', $suggestedTags);
        $response->assign('unknownTags', $unknownTags);
        $response->assign('endpointUrl', $this->endpointUrlService->getEndpointAddress());
        $response->assign('danbooruApiUrl', $this->configuration->get('danbooru_api_url'));
        $response->assign('suggestedTagsLimit', (int) $this->configuration->get('limit_for_suggested_tags'));

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

    /**
     * @param TagCollection $suggestedTags All suggested tags
     * @param TagCollection $filteredTags Reduced filter collection from the machine learning platform,
     *                                    reduced by Danbooru tags.
     *
     * @return string
     */
    public function tagsCssClassHelperUnknownTags(TagCollection $suggestedTags, TagCollection $filteredTags): string
    {
        $mlpTagList = '';

        foreach ($suggestedTags->getTags() as $tag) {
            $isNew = false;

            foreach ($filteredTags->getTags() as $filteredTag) {
                if ($tag->getName() === $filteredTag->getName()) {
                    $isNew = true;
                    break;
                }
            }

            if ($isNew) {
                $mlpTagList .= '<div class="tag suggested-tags unknownTag">' . $this->addWikiLink($tag->getName()) . '&nbsp;</div>';
            } else {
                $mlpTagList .= '<div class="tag suggested-tags">' . $this->addWikiLink($tag->getName()) . '&nbsp;</div>';
            }
        }

        return $mlpTagList;
    }

    protected function addWikiLink(string $title): string
    {
        $url = '<a href="https://danbooru.donmai.us/wiki_pages/%s" class="suggested-tag" target="_blank" rel="noreferrer">%s</a>';
        $url = sprintf($url, \htmlentities($title), $title);
        return $url;
    }

    public function tagsCssClassHelperColoredDanbooruTags(Tag $tag): string
    {
        return '<span style="color: '. $tag->getHexColor().';">'.$tag->getName().'</span>';
    }
}
