<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\DataType;

use SplFileObject;

class Picture
{
    private SplFileObject $file;

    /** @var string[] */
    private array $dominantColors = [];

    /**
     * @return SplFileObject
     */
    public function getFile(): SplFileObject
    {
        return $this->file;
    }

    /**
     * @param SplFileObject $file
     */
    public function setFile(SplFileObject $file): void
    {
        $this->file = $file;
    }

    /**
     * @see https://www.php.net/manual/de/class.splfileobject.php#113149
     */
    public function destroyFileObject(): void
    {
        unset($this->file);
    }

    /**
     * @return string[]
     */
    public function getDominantColors(): array
    {
        return $this->dominantColors;
    }

    /**
     * @param string[] $dominantColors
     */
    public function setDominantColors(array $dominantColors): void
    {
        $this->dominantColors = $dominantColors;
    }
}
