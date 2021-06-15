<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\ApiContract;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AuthenticationError;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;
use Ramsterhad\DeepDanbooruTagAssist\Application\System\Json;

class Danbooru implements ApiContract
{
    private Post $post;

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

    public function authenticate(Endpoint $endpoint, string $username, string $apiKey): bool
    {
        $response = $endpoint->authenticate(Config::get('danbooru_api_url'), $username, $apiKey);

        // No json as return value. This is bad.
        if (!Json::isJson($response)) {
            throw new AuthenticationError(
                'The authentication service didn\'t return a nice response. -_-\'',
                AuthenticationError::CODE_RESPONSE_CONTAINED_INVALID_JSON
            );
        }

        $response = \json_decode($response);

        // Json didn't had the id property which every logged in user must have.
        if (!\property_exists($response, 'id')) {
            throw new AuthenticationError(
                'Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.',
                AuthenticationError::CODE_RESPONSE_MISSING_PROPERTIES
            );
        }

        return true;
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
     *                           Typically for variations or reuploads in higher resolution
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
     * @param TagCollection $tagCollection
     * @param Post $post
     * @param Endpoint $endpoint
     * @param string $url
     * @param string $username
     * @param string $apiKey
     * @throws Exception\EndpointException
     * @throws PostResponseException
     */
    public function requestTags(
        TagCollection $tagCollection,
        Post $post,
        Endpoint $endpoint
    ): void {

        $response = $endpoint->requestPost(
            self::loadEndpointAddress(),
            Session::get('username'),
            Session::get('api_key')
        );

        // Check if the result is a valid json
        if (!Json::isJson($response)) {
            throw new PostResponseException(
                'Error! Return value has to be a valid JSON, but I got something... strange &#45576;_&#45576;.',
                PostResponseException::CODE_INVALID_JSON
            );
        }

        /*
         * We want the transformed json to be an object (an object wrapped in an array).
         * Since this function promises to return an array, we don't need a further check if the return value is
         * an array actually. If it wouldn't then it would break at this very place.
         */
        $response = $this->transformJsonStringToObject($response);

        // The API URL must be set with limit=1, indicating the API to return only 1 result.
        if (count($response) > 1) {
            throw new PostResponseException(
                'Oh wow! Got way too much results! Pls check your API query. (&#180;&#65381;&#30410;&#65381;&#65344;*)',
                PostResponseException::CODE_JSON_CONTAINS_MORE_THAN_ONE_ITEM
            );
        }

        // We got less than 0 result? Nothing?
        if (count($response) === 0) {
            throw new PostResponseException(
                'Got nothing. &#175;\\_(&#12484;)_/&#175; Pls reload.',
                PostResponseException::CODE_JSON_CONTAINS_NO_ITEM
            );
        }

        // We said earlier, that the json string has to be transformed to be an object.
        $object = $response[0];

        if (!is_object($object)) {
            throw new PostResponseException(
                'That\'s not an object. What. Is. This.?',
                PostResponseException::CODE_JSON_ITEM_IS_NOT_OBJECT
            );
        }

        if (property_exists($object, 'success') && $object->success === false) {
            throw new AuthenticationError(
                'Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.',
                AuthenticationError::CODE_RESPONSE_INVALID_CREDENTIALS
            );
        }

        /*
         * Basic members don't have access to 'fringe' content. In that case, the API does not return the Id
         * Example Id: @todo still to fill in
         */
        foreach ($this->getListOfRequiredJsonPropertiesFromDanbooruResponse() as $item) {
            if (!property_exists($object, $item)) {
                throw new PostResponseException(
                    '( &#865;&#3232; &#662;&#815; &#865;&#3232;) Can\'t show you that. Maybe you don\'t have the permission to see the post?',
                    PostResponseException::CODE_JSON_ITEM_IS_MISSING_PROPERTIES
                );
            }
        }

        // Fills a collection of tags with the various tag categories by Danbooru.
        $this->transformTagStringListsToCollection($object, $tagCollection);

        /*
         * We have now all the information we need to transform the data into a Post object, which represents one entry
         * from Danbooru; normalised for our needs.
         */
        $this->post = $this->convertResponseObjectToPostObject($post, $object, $tagCollection);
    }

    /**
     * The method returns an array, but the function \json_decode is set to associative false. This is as we want it,
     * as we expect from Danbooru a json, which has multiple objects (that's why associative is false), but wrapped in
     * an array. So we will have an array with one item as result. And this item is converted to an object, instead of
     * an associative array.
     *
     * @param string $json
     * @return \stdClass[]
     */
    protected function transformJsonStringToObject(string $json): array
    {
        $result = \json_decode($json, false);

        if (!is_array($result)) {
            $result = [$result];
        }

        return $result;
    }

    protected function convertResponseObjectToPostObject(Post $post, \stdClass $object, TagCollection $tagCollection): Post
    {
        return $post
            ->setId((string) $object->id)
            ->setPicPreview($object->preview_file_url)
            ->setPicOriginal($object->file_url)
            ->setPicLarge($object->large_file_url)
            ->setTagCollection($tagCollection)
        ;
    }

    /**
     * Tags have colors which describe their membership to the categories:
     * artist, copyright, character, general, meta
     *
     * @param \stdClass $object
     * @param TagCollection $tagCollection
     * @throws PostResponseException
     */
    protected function transformTagStringListsToCollection(\stdClass $object, TagCollection $tagCollection): void
    {
        if (!property_exists($object, 'tag_string_artist')) {
            throw new PostResponseException('Missing property "tag_string_artist".');
        }

        if (!property_exists($object, 'tag_string_copyright')) {
            throw new PostResponseException('Missing property "tag_string_copyright".');
        }

        if (!property_exists($object, 'tag_string_character')) {
            throw new PostResponseException('Missing property "tag_string_character".');
        }

        if (!property_exists($object, 'tag_string_general')) {
            throw new PostResponseException('Missing property "tag_string_general".');
        }

        if (!property_exists($object, 'tag_string_meta')) {
            throw new PostResponseException('Missing property "tag_string_meta".');
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
     * @param string $tags
     * @param string $color
     * @param TagCollection $collection
     * @throws \Exception
     */
    protected function addTagsFromResponseObjectToCollection(string $tags, string $color, TagCollection $collection): void
    {
        // Nothing to do here, if $tags is an empty string.
        if (empty($tags)) {
            return;
        }

        if (strpos($color, '#') === false) {
            throw new \Exception('I need a hex color, nothing else!');
        }

        $tags = preg_split('/ /', $tags);

        foreach ($tags as $item) {
            $collection->add(new Tag($item, '0.0', $color));
        }
    }

    /**
     * @param Endpoint $endpoint
     * @param int $id
     * @param TagCollection $collection
     * @throws Exception\EndpointException
     */
    public function pushTags(Endpoint $endpoint, int $id, TagCollection $collection ): void {

        $endpoint->pushTags(
            Config::get('danbooru_api_url'),
            Session::get('username'),
            Session::get('api_key'),
            $id,
            $collection
        );
    }

    /**
     * This function compares the Danbooru tags with given ones and returns a tag collection of unknown tags.
     *
     * @param TagCollection $collection
     * @return TagCollection
     *
     */
    public function filterTagsAgainstAlreadyKnownTags(TagCollection $collection): TagCollection
    {
        $unknownTagCollection = new TagCollection();

        foreach ($collection->getTags() as $tag) {

            $knownTag = false; //Unknown by default, unless proven known

            foreach ($this->post->getTags() as $danbooruTag) {

                if (trim($danbooruTag->getName()) === trim($tag->getName())) {
                    // Tag is already known on danbooru:
                    $knownTag = true;
                    continue;
                }
            }

            // Add unknown (!known) tags to $unknownTagCollection
            if (!$knownTag) {
                $unknownTagCollection->add($tag);
            }
        }

        return $unknownTagCollection;
    }

    /**
     * @return string[]
     */
    public function getListOfRequiredJsonPropertiesFromDanbooruResponse(): array
    {
        return $this->listOfRequiredJsonPropertiesFromDanbooruResponse;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getCollection(): TagCollection
    {
        return $this->post->getTagCollection();
    }

    public static function getGetPostUrlFromConfig(): string
    {
        return Config::get('danbooru_api_url') . 'posts.json?' . Config::get('danbooru_default_request');
    }

    public static function loadEndpointAddress(): string
    {
        return $_COOKIE['danbooru_api_url'] ?? self::getGetPostUrlFromConfig();
    }
}
