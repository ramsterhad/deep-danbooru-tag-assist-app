<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Service;

use Exception;
use Ramsterhad\DeepDanbooruTagAssist\Application\Kernel;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception\DirectoryNotFound;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception\DirectoryOrFileNotWriteable;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Infrastructure\Repository;

/**
 * @TODO Application
 */
class TemporaryFileService
{
    private ConfigurationInterface $configuration;
    private Repository $repository;

    public function __construct(
        ConfigurationInterface $configuration,
        Repository $repository
    ) {
        $this->configuration = $configuration;
        $this->repository = $repository;
    }

    /**
     * @throws DirectoryOrFileNotWriteable
     * @throws DirectoryNotFound
     * @throws Exception
     */
    public function writeFileToTemporaryDirectory(string $filename, string $data): string
    {
        $pathToStorage = $this->buildPathToTemporaryStorage();

        return $this->repository->write($filename, $pathToStorage, $data);
    }

    /**
     * @TODO Services, not static calls. Cut the dependency out
     * @return string
     * @throws Exception
     */
    private function buildPathToTemporaryStorage(): string
    {
        return Kernel::getBasePath() . $this->configuration->get('picture_storage') . DIRECTORY_SEPARATOR;
    }
}
