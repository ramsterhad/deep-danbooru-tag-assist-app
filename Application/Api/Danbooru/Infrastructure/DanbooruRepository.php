<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Exception\AdapterApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Shared\Exception\ApplicationException;

final class DanbooruRepository
{
    /**
     * @throws AdapterApplicationException
     */
    public function authenticate(AdapterInterface $adapter, string $url): string {
        $adapter
            ->init()
            ->sendTo($url)
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(3)
            ->execute()
            ->hangUp();

        return $adapter->getResponse();
    }

    /**
     * @throws AdapterApplicationException
     */
    public function requestPost(AdapterInterface $adapter, string $url, string $username, string $apiKey): string
    {
        $adapter
            ->init()
            ->sendTo($url)
            ->authenticateWith($username, $apiKey)
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(3)
            ->execute()
            ->hangUp();

        return $adapter->getResponse();
    }

    /**
     * @throws AdapterApplicationException
     */
    public function pushTags(
        AdapterInterface $adapter,
        string $url,
        string $username,
        string $apiKey,
        array $data
    ): string {
        $adapter
            ->init()
            ->sendTo($url)
            ->authenticateWith($username, $apiKey)
            ->sendData($data)
            ->byMethod('PUT')
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(120)
            ->execute();

        return $adapter->getResponse();
    }

    public function requestTagsByPost(
        AdapterInterface $adapter,
        string $url,
        Post $post,
    ): TagCollection {

        // https://danbooru.donmai.us/ai_tags.json?search[media_asset_id]=9266755
        // todo: catch bad IDs: https://danbooru.donmai.us/posts/8127593.json
        $urlTagIds = sprintf('%sai_tags.json?search[media_asset_id]=%d', $url, $post->getMediaAssetId());

        $adapter
            ->init()
            ->sendTo($urlTagIds)
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(3)
            ->execute()
            ->hangUp();

        //todo make it error prone!
        $tagIds = $adapter->getResponse();
        $tagIds = json_decode($tagIds);

        /* todo
        if (!property_exists($mediaAsset, 'tag_string')) {
            throw new ApplicationException('Media asset should have a tag_string!' . print_r($mediaAsset));
        }
        */

        $tags = [];
        $tagIdList = [];

        foreach ($tagIds as $tagId) {
            $tagIdList[] = (int) $tagId->tag_id;
            $tags[(int) $tagId->tag_id][0] = '';
            $tags[(int) $tagId->tag_id][1] = (int) $tagId->score;
        }

        $tagIdList = implode(',', $tagIdList);
        $urlTagNames = sprintf('%stags.json?search[id]=%s', $url, $tagIdList);

        $adapter
            ->init()
            ->sendTo($urlTagNames)
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(3)
            ->execute()
            ->hangUp();

        //todo make it error prone!
        $tagNames = $adapter->getResponse();
        $tagNames = json_decode($tagNames);

        foreach ($tagNames as $tagName) {
            $tags[$tagName->id][0] = $tagName->name;
        }

        $collection = new TagCollection();

        // In case there are no tags.
        if (!is_array($tags)) {
            return $collection;
        }

        foreach ($tags as $item) {
            $tag = new Tag($item[0], (string) $item[1]);
            $collection->add($tag);
        }
        return $collection;
    }

    /**
     * @throws AdapterApplicationException
     */
    public function downloadPicture(AdapterInterface $adapter, string $url): string
    {
        $adapter
            ->init()
            ->sendTo($url)
            ->requestTransferStatus(true)
            ->activateAutoReferer(false)
            ->withHttpVersion(2)
            ->includeHeaderInResponse(false)
            ->execute();

        return $adapter->getResponse();
    }
}
