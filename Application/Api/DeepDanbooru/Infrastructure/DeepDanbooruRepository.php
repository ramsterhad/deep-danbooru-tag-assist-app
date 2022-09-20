<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Exception\AdapterApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use function json_decode;
use function sprintf;


final class DeepDanbooruRepository
{
    private static $endpointUrl = 'https://deepdanbooru.donmai.us/';

    /**
     * @throws AdapterApplicationException
     */
    public function requestTagsByPictureUrl(
        AdapterInterface $adapter,
        string $pictureUrl,
    ): TagCollection {

        // https://deepdanbooru.donmai.us/?url=%s
        $url = sprintf('%s?url=%s', self::$endpointUrl, $pictureUrl);

        $adapter
            ->init()
            ->sendTo($url)
            ->requestTransferStatus(true)
            ->waitForFirstByte(3)
            ->waitForFinishingTheRequest(3)
            ->execute()
            ->hangUp();

        $response = $adapter->getResponse();
        $tags = json_decode($response);
        $collection = new TagCollection();

        // In case there are no tags.
        if (!is_array($tags)) {
            return $collection;
        }

        foreach ($tags as $tag) {
            $collection->add(new Tag($tag[0], (string) $tag[1]));
        }
        return $collection;
    }
}
