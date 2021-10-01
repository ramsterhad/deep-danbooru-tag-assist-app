<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\InvalidCredentials;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\RequestPostApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory\PostFactory;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture\DominantColorsService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture\DownloadPictureService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Session;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Utility\Json;
use stdClass;

use function count;
use function is_array;
use function is_object;
use function json_decode;
use function preg_split;
use function property_exists;
use function str_contains;

final class RequestPostService
{
    private DanbooruBridgeService $danbooruBridgeService;
    private DominantColorsService $dominantColorsService;
    private DownloadPictureService $downloadPictureService;
    private EndpointUrlService $endpointUrlService;

    private array $listOfRequiredJsonPropertiesFromDanbooruResponse = [
        'id',
        'tag_string',
        'tag_string_general',
        'tag_string_character',
        'tag_string_copyright',
        'tag_string_artist',
        'tag_string_meta',
        'preview_file_url',
        'file_url',
        'large_file_url',
    ];

    public function __construct(
        DanbooruBridgeService $danbooruBridgeService,
        DominantColorsService $dominantColorsService,
        DownloadPictureService $downloadPictureService,
        EndpointUrlService $endpointUrlService,
    ) {
        $this->danbooruBridgeService = $danbooruBridgeService;
        $this->dominantColorsService = $dominantColorsService;
        $this->downloadPictureService = $downloadPictureService;
        $this->endpointUrlService = $endpointUrlService;
    }

    /**
     * The API returns the following 49 fields:
     *
     * Field                     Description and notes
     *
     * id                        Submission ID
     * created_at                Date, time and timezone at submission
     * updated_at                Date, time and timezone at last (tag?) field edit
     * up_score                  No. of upvotes, can only be done by gold members and higher ranks
     * down_score                No. of downvotes, can only be done by gold members and higher ranks
     * score                     Calculated sum of up_score and down_score
     * source                    URL or description of submission origin, if from pixiv, pixiv_id is populated
     * md5                       MD5 hash. Occasionally NOT correct
     * rating                    s/q/e
     * is_note_locked            Moderators and higher ranks can disable the creation of notes; text boxes
     *                           positioned at specified image coordinates
     * is_rating_locked          Moderators and higher ranks can disable rating editing
     * is_status_locked          Moderators and higher ranks can disable status editing
     *                           (status: active/any/appealed/banned/deleted/flagged/modqueue/pending/unmoderated
     * is_pending                Submission is not yet approved, but is visible and accessible
     * is_flagged                Submission is flagged for deletion, but is visible and accessible
     * is_deleted                Submission is deleted. Danbooru makes a distinction between soft-deleted (=hidden
     *                           but accessible) and hard-deleted (not accessible, example: ID 6)
     * uploader_id               User ID of uploader
     * approver_id               User ID of approver
     * pool_string               Submissions can be added to one or more pools (a group)
     * last_noted_at             Date, time and timezone at last note change
     * last_comment_bumped_at    Date, time and timezone at last comment bump. When commenting, users can choose
     *                           not to "bump" with a checkbox.
     * fav_count                 No. of times added to favourites
     * tag_string                All tags
     * tag_count                 No. of tags (general+artist+character+copyright+meta), does not include rating:s/q/e
     * tag_count_general         No. of general tags (descriptive tags)
     * tag_count_artist          No. of artist tags (most often 1, unless authored by multiple artists)
     * tag_count_character       No. of character tags (Character names). Note that posts containing original
     *                           characters are typically not tagged with character tags
     * tag_count_copyright       No. of copyright tags (Series names)
     * file_ext                  Extension
     * file_size                 In bytes
     * image_width               In pixels
     * image_height              In pixels
     * parent_id                 In addition to pools, submissions can have a "parent" and/or a "child".
     *                           Typically, for variations or reuploads in higher resolution
     * has_children              See parent_id
     * is_banned                 The artist requested removal of the submission. Does not imply status:deleted
     * pixiv_id                  See source
     * last_commented_at         Date, time and timezone of last comment. Also see last_comment_bumped_at
     * has_active_children       Not sure?
     * bit_flags                 Not sure?
     * tag_count_meta            No. of meta tags
     * has_large                 If image_width and/or image_height >= 850, a resized 850px image is generated
     * has_visible_children      Not sure?
     * tag_string_general        Same as tag_string, only general tags
     * tag_string_character      Same as tag_string, only character tags
     * tag_string_copyright      Same as tag_string, only copyright tags
     * tag_string_artist         Same as tag_string, only artist tags
     * tag_string_meta           Same as tag_string, only meta tags
     * file_url                  Link to original (full resolution) file
     * large_file_url            Link to resized (850 px) file
     * preview_file_url          Link to thumbnail
     *
     * @throws InvalidCredentials
     * @throws PostResponseApplicationException
     */
    public function request(): Post
    {
        $tagCollection = new TagCollection();

        try {
            $response = $this->danbooruBridgeService->requestPost(
                $this->endpointUrlService->getEndpointAddress(),
                Session::get('username'),
                Session::get('api_key')
            );
        } catch (RequestPostApplicationException $e) {
            throw new PostResponseApplicationException(
                PostResponseApplicationException::MESSAGE_INVALID_JSON,
                PostResponseApplicationException::CODE_INVALID_JSON
            );
        }


        // Check if the result is a valid json
        if (!Json::isJson($response)) {
            throw new PostResponseApplicationException(
                PostResponseApplicationException::MESSAGE_INVALID_JSON,
                PostResponseApplicationException::CODE_INVALID_JSON
            );
        }


        /*
         * We want the transformed json to be an object (an object wrapped in an array).
         * Since this function promises to return an array, we don't need a further check if the return value is
         * an array, actually. If it wouldn't then it would break at this very place.
         */
        $response = $this->transformJsonStringToObject($response);


        // The API URL must be set with limit=1, indicating the API to return only 1 result.
        if (count($response) > 1) {
            throw new PostResponseApplicationException(
                PostResponseApplicationException::MESSAGE_JSON_CONTAINS_MORE_THAN_ONE_ITEM,
                PostResponseApplicationException::CODE_JSON_CONTAINS_MORE_THAN_ONE_ITEM
            );
        }

        // We got less than 0 result? Nothing?
        if (count($response) === 0) {
            throw new PostResponseApplicationException(
                PostResponseApplicationException::MESSAGE_JSON_CONTAINS_NO_ITEM,
                PostResponseApplicationException::CODE_JSON_CONTAINS_NO_ITEM
            );
        }


        // We said earlier the json string has to be transformed to be an object.
        $object = $response[0];

        if (!is_object($object)) {
            throw new PostResponseApplicationException(
                PostResponseApplicationException::MESSAGE_JSON_ITEM_IS_NOT_OBJECT,
                PostResponseApplicationException::CODE_JSON_ITEM_IS_NOT_OBJECT
            );
        }

        // Wrong credentials.
        if (
            property_exists($object, 'success') &&
            $object->success === false &&
            str_contains($object->message, 'SessionLoader::AuthenticationFailure')
        ) {
            throw new InvalidCredentials(
                InvalidCredentials::MESSAGE_RESPONSE_INVALID_CREDENTIALS,
                InvalidCredentials::CODE_RESPONSE_INVALID_CREDENTIALS
            );
        }

        // Any other error message directly from Danbooru.
        if (property_exists($object, 'success') && $object->success === false) {
            throw new PostResponseApplicationException(
                'Danbooru said "'.$object->message.'". (╯︵╰,)',
                PostResponseApplicationException::CODE_DANBOORU_ERROR_MESSAGE
            );
        }


        // Basic members don't have access to 'fringe' content. In that case, the API does not return the id.
        foreach ($this->listOfRequiredJsonPropertiesFromDanbooruResponse as $item) {
            if (!property_exists($object, $item)) {
                throw new PostResponseApplicationException(
                    PostResponseApplicationException::MESSAGE_JSON_ITEM_IS_MISSING_PROPERTIES,
                    PostResponseApplicationException::CODE_JSON_ITEM_IS_MISSING_PROPERTIES
                );
            }
        }

        // Fills a collection with tags with the various tag categories by Danbooru.
        $this->transformTagStringListsToCollection($object, $tagCollection);

        /*
         * We have now all the information we need to transform the data into a Post object, which represents one entry
         * from Danbooru; normalised for our needs.
         */
        $post = $this->convertResponseObjectToPostObject($object, $tagCollection);

        $post->setPicture(
            $this->downloadPictureService->download($post)
        );

        $post->getPicture()->setDominantColors(
            $this->dominantColorsService->calculateDominantColors($post)
        );

        return $post;
    }


    /**
     * The method returns an array, but the function \json_decode is set to associative false. This is as we want it,
     * as we expect from Danbooru a json, which has multiple objects (that's why associative is false), but wrapped in
     * an array. So we will have an array with one item as result. And this item is converted to an object, instead of
     * an associative array.
     *
     * @return stdClass[]
     */
    protected function transformJsonStringToObject(string $json): array
    {
        $result = json_decode($json, false);

        if (!is_array($result)) {
            $result = [$result];
        }

        return $result;
    }

    /**
     * Tags have colors to quickly show their category: The categories are: artist, copyright, character, general, meta.
     * The method assigns the correct category color to each tag.
     *
     * @throws PostResponseApplicationException
     * @throws Exception
     */
    protected function transformTagStringListsToCollection(stdClass $object, TagCollection $tagCollection): void
    {
        if (!property_exists($object, 'tag_string_artist')) {
            throw new PostResponseApplicationException('Missing property "tag_string_artist".');
        }

        if (!property_exists($object, 'tag_string_copyright')) {
            throw new PostResponseApplicationException('Missing property "tag_string_copyright".');
        }

        if (!property_exists($object, 'tag_string_character')) {
            throw new PostResponseApplicationException('Missing property "tag_string_character".');
        }

        if (!property_exists($object, 'tag_string_general')) {
            throw new PostResponseApplicationException('Missing property "tag_string_general".');
        }

        if (!property_exists($object, 'tag_string_meta')) {
            throw new PostResponseApplicationException('Missing property "tag_string_meta".');
        }

        $this->addTagsFromResponseObjectToCollection($object->tag_string_artist, Tag::DANBOORU_TAG_HEXCOLOR_ARTIST, $tagCollection);
        $this->addTagsFromResponseObjectToCollection($object->tag_string_copyright,Tag::DANBOORU_TAG_HEXCOLOR_COPYRIGHT, $tagCollection);
        $this->addTagsFromResponseObjectToCollection($object->tag_string_character, Tag::DANBOORU_TAG_HEXCOLOR_CHARACTER, $tagCollection);
        $this->addTagsFromResponseObjectToCollection($object->tag_string_general, Tag::DANBOORU_TAG_HEXCOLOR_GENERAL, $tagCollection);
        $this->addTagsFromResponseObjectToCollection($object->tag_string_meta, Tag::DANBOORU_TAG_HEXCOLOR_META, $tagCollection);
    }

    /**
     * Splits a tag string like "2019_rugby_world_cup fate/extra fate_(series)" into pieces:
     * 2019_rugby_world_cup, fate/extra, fate_(series).
     *
     * Returns the pieces collected as a collection of tags.
     *
     * Since the tags are from Danbooru, the score is set to 0.0 as this information is part of a Tag object,
     * but in this case not necessary.
     *
     * @throws Exception
     */
    protected function addTagsFromResponseObjectToCollection(string $tags, string $color, TagCollection $collection): void
    {
        // Nothing to do here, if $tags is an empty string.
        if (empty($tags)) {
            return;
        }

        if (!str_contains($color, '#')) {
            throw new Exception('I need a hex color, nothing else!');
        }

        $tags = preg_split('/ /', $tags);

        foreach ($tags as $item) {
            $collection->add(new Tag($item, '0.0', $color));
        }
    }

    protected function convertResponseObjectToPostObject(stdClass $object, TagCollection $tagCollection): Post
    {
        /** @var PostFactory $factory */
        $factory = ContainerFactory::getInstance()->getContainer()->get(PostFactory::class);
        $entity = $factory->create();

        return $entity
            ->setId((string) $object->id)
            ->setPicPreview($object->preview_file_url)
            ->setPicOriginal($object->file_url)
            ->setPicLarge($object->large_file_url)
            ->setTagCollection($tagCollection);
    }
}
