<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PushTagsException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

use function sprintf;

class PushTagsService
{
    private DanbooruBridgeService $danbooruBridgeService;

    public function __construct(DanbooruBridgeService $bridgeService)
    {
        $this->danbooruBridgeService = $bridgeService;
    }

    /**
     * @throws PushTagsException
     */
    public function pushTags(
        string $url,
        string $username,
        string $apiKey,
        int $id,
        TagCollection $collection
    ): string {

        $url = sprintf('%sposts/%s.json', $url, $id);
        $data = ['post[tag_string]' => $collection->toString()];

        return $this->danbooruBridgeService->pushTags($url, $username, $apiKey, $data);
    }
}
