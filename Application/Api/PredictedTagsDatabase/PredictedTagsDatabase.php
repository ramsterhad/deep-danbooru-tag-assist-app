<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\ApiContract;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\DatabaseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\PredictedTagsDatabaseInvalidResponseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\System\Json;

class PredictedTagsDatabase implements ApiContract
{
    private TagCollection $collection;

    /**
     * @param string $id
     * @throws PredictedTagsDatabaseInvalidResponseException
     * @throws \JsonException
     * @throws DatabaseException
     */
    public function requestTags(string $id): void
    {
        $response = (new Database())->get((int) $id);

        if (!Json::isJson($response)) {
            throw new \JsonException('The predicted tags database did not return a valid json for id "' . $id . '".');
        }

        // Contains the keys id and tags. tags is an array with items: 0 -> score, 1 -> tag name.
        $jsonAsArray = \json_decode($response, true);

        if (!\array_key_exists('id', $jsonAsArray)) {
            throw new PredictedTagsDatabaseInvalidResponseException('Missing property: id.');
        }

        if (!\array_key_exists('tags', $jsonAsArray)) {
            throw new PredictedTagsDatabaseInvalidResponseException('Missing property: tags.');
        }

        $this->collection = new TagCollection();

        foreach ($jsonAsArray['tags'] as $item) {
            $this->collection->add(
                new Tag($item[1], $item[0])
            );
        }
    }

    public function getEndpointAddress(): string
    {
        return Config::get('predicted_tags_db_url');
    }

    /**
     *
     * This function compares the Danbooru tags with the found ones from the MLP.
     * Only unknown tags, like found tags by the MLP which are not listed at Danbooru are returned.
     *
     * @param TagCollection $tagsDanbooru
     * @return TagCollection
     *
     */
    public function filterTagsFromMlpAgainstAlreadyKnownTags(TagCollection $tagsDanbooru): TagCollection
    {
        $unknownTagCollection = new TagCollection();

        foreach ($this->collection->getTags() as $tag) {

            $knownTag = false; //Unknown by default, unless proven known

            foreach ($tagsDanbooru->getTags() as $danbooruTag) {

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

    public function getCollection(): TagCollection
    {
        return $this->collection;
    }
}
