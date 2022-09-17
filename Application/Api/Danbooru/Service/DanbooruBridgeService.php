<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Adapter\AdapterInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AdapterApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PushTagsApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\RequestPostApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure\DanbooruRepository;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;

use function sprintf;

final class DanbooruBridgeService
{
    private DanbooruRepository $repository;

    public function __construct(DanbooruRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate(string $url, string $username, string $apiKey): string
    {
        /** @var AdapterInterface $adapter */
        $adapter = ContainerFactory::getInstance()->getContainer()->get(AdapterInterface::class);

        $apiRequestUrl = sprintf(
            '%sprofile.json?login=%s&api_key=%s',
            $url,
            $username,
            $apiKey,
        );

        return $this->repository->authenticate($adapter, $apiRequestUrl);
    }

    /**
     * @throws RequestPostApplicationException
     */
    public function requestPost(string $url, string $username, string $apiKey): string
    {
        /** @var AdapterInterface $adapter */
        $adapter = ContainerFactory::getInstance()->getContainer()->get(AdapterInterface::class);

        try {

            // Danbooru doesn't like the char sequence :> and :< .
            $url = str_replace([':<', ':>'], [':%3C', ':%3E'], $url);

            return $this->repository->requestPost($adapter, $url, $username, $apiKey);
        } catch (AdapterApplicationException $e) {
            throw new RequestPostApplicationException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws PushTagsApplicationException
     */
    public function pushTags(
        string $url,
        string $username,
        string $apiKey,
        array $data
    ): string {

        /** @var AdapterInterface $adapter */
        $adapter = ContainerFactory::getInstance()->getContainer()->get(AdapterInterface::class);

        try {
            return $this->repository->pushTags($adapter, $url, $username, $apiKey, $data);
        } catch (AdapterApplicationException $e) {
            throw new PushTagsApplicationException();
        }
    }
}
