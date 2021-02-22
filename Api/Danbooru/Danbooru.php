<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Api\ApiContract;
use Ramsterhad\DeepDanbooruTagAssist\Api\Tag\Collection;
use Ramsterhad\DeepDanbooruTagAssist\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\System\Json;

class Danbooru implements ApiContract
{
    private string $endpoint;

    private Post $post;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function login(string $username, string $apiKey): bool
    {
        $loginApiRequest = sprintf('%sprofile.json?login=%s&api_key=%s',
            $this->endpoint,
            $username,
            $apiKey,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginApiRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        // No json as return value. This is bad.
        if (!Json::isJson($result)) {
            throw new \Exception('The login service didn\'t return a nice response. -_-\'');
        }

        $result = json_decode($result);

        // The json couldn't be transformed to an object.
        if (!is_object($result)) {
             throw new \Exception('Why is it like that? Why can\'t I get a clean answer?!');
        }

        // Json didn't had the id property which every logged in user must have.
        if (!property_exists($result, 'id')) {
            throw new \Exception('Excuse me? Whats your name and/or api key again? must. know. that.');
        }

        return true;
    }

    public function requestTags(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!empty(Config::get('danbooru_user')) || !empty(Config::get('danbooru_pass'))) {
            curl_setopt($ch, CURLOPT_USERPWD, Config::get('danbooru_user') . ':' . Config::get('danbooru_pass'));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        
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
         *  positioned at specified image coordinates
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
         */

        if (!Json::isJson($result)) {
            $type = gettype($result);
            $returnValue = htmlentities(substr($result, 0, 30));

            throw new \Exception(sprintf(
                'Error! Return value from the API URL %s must be a valid JSON but I got %s, of type %s.',
                $this->endpoint, $returnValue, $type
            ));

        }

        $object = json_decode($result);

        // If the return value is an object at this point, something went wrong.
        if (is_object($object) && property_exists($object, 'success') && $object->success === false) {
            throw new \Exception('(╯︵╰,) Danbooru said no to your credentials.');
        }

        // It needs to be an array or else we have an exception.
        if (!is_array($object)) {
            throw new \Exception('Got an unexpected format. ⦿⽘⦿. Pls reload.');
        }

        // We only should get one result. Not less, not more.
        if (count($object) > 1) {
            throw new \Exception('Got too much O.O\'. Pls reload.');
        }

        // We got less than 0 result? Nothing?
        if (count($object) === 0) {
            throw new \Exception('Got nothing. Again. Just nothing. -_-. Pls reload.');
        }

        $object = $object[0];

        $mustExist = [
            'id',
            'tag_string',
            'preview_file_url',
            'file_url',
            'large_file_url',
        ];

        foreach ($mustExist as $item) {
            if (!property_exists($object, $item)) {
                throw new \Exception(
                    sprintf(
                        'Maybe you don\'t have the permission to see the post?',
                        $item,
                        $this->endpoint
                    )
                );
            }
        }

        $collection = Post::convertDanbooruTagStringToCollection($object->tag_string);

        $this->post = new Post(
            (string) $object->id,
            $object->preview_file_url,
            $object->file_url,
            $collection,
            $object->large_file_url
        );
    }

    public function pushTags(int $id, Collection $collection)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Config::get('danbooru_api_url') . 'posts/' . $id . '.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($ch, CURLOPT_POSTFIELDS, ['post[tag_string]' => $collection->toString()]);
        if (!empty(Config::get('danbooru_user')) || !empty(Config::get('danbooru_pass'))) {
            curl_setopt($ch, CURLOPT_USERPWD, Config::get('danbooru_user') . ':' . Config::get('danbooru_pass'));
        }
        $result = curl_exec($ch);

        curl_close($ch);
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getCollection(): Collection
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
