<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AdapterException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

final class Repository
{
    /**
     * @throws AdapterException
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
     * @throws AdapterException
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
     * @throws AdapterException
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
}
