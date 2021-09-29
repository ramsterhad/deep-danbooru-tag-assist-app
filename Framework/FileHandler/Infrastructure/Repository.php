<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception\DirectoryNotFound;
use Ramsterhad\DeepDanbooruTagAssist\Framework\FileHandler\Exception\DirectoryOrFileNotWriteable;

use function substr;

use const DIRECTORY_SEPARATOR;
use const FILE_APPEND;

class Repository
{
    /**
     * @throws DirectoryOrFileNotWriteable
     * @throws DirectoryNotFound
     */
    public function write(string $filename, string $pathToStorage, string $data, $overwrite = true): string
    {
        if (substr($pathToStorage, -1) !== DIRECTORY_SEPARATOR) {
            $pathToStorage .= DIRECTORY_SEPARATOR;
        }

        if (!file_exists($pathToStorage)) {
            throw new DirectoryNotFound($pathToStorage);
        }

        if (!is_writeable($pathToStorage)) {
            throw new DirectoryOrFileNotWriteable($pathToStorage);
        }

        $pathToStorageWithFilename = $pathToStorage . $filename;

        if ($overwrite) {
           return $this->overwrite($pathToStorageWithFilename, $data);
        }

        return $this->append($pathToStorageWithFilename, $data);
    }

    /**
     * Returns the complete path to the file if it was written. Else an exception will be thrown.
     *
     * @throws DirectoryOrFileNotWriteable
     */
    private function overwrite(string $pathToStorageWithFilename, string $data): string
    {
        if (file_put_contents($pathToStorageWithFilename, $data) === false) {
            throw new DirectoryOrFileNotWriteable($pathToStorageWithFilename);
        }
        return $pathToStorageWithFilename;
    }

    /**
     * Returns the complete path to the file if it was written. Else an exception will be thrown.
     *
     * @throws DirectoryOrFileNotWriteable
     */
    private function append(string $pathToStorageWithFilename, string $data): string
    {
        if (file_put_contents($pathToStorageWithFilename, $data, FILE_APPEND) == false) {
            throw new DirectoryOrFileNotWriteable($pathToStorageWithFilename);
        }
        return $pathToStorageWithFilename;
    }
}
