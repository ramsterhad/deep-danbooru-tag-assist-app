<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\DeepDanbooru\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Exception\AdapterApplicationException;


final class DeepDanbooruRepository
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

    public function requestTagsForPicture(AdapterInterface $adapter, string $url, string $pictureUrl)
    {


        // https://deepdanbooru.donmai.us/?url=


        $adapter
            ->init()
            ->sendTo($url)
            ->requestTransferStatus(true)
            ->activateAutoReferer(false)
            ->withHttpVersion(2)
            ->includeHeaderInResponse(false)
            ->execute();

        print_r($adapter->getResponse());
        exit;

        return $adapter->getResponse();
    }
}
