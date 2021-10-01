<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\DataType\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Kernel;

use function exec;
use function sprintf;

class DominantColorsRepository
{
    /**
     * @return string[]
     */
    public function getDominantColors(Picture $picture): array
    {
        $pathToScript = $this->buildPathToScript();
        $pathToPicture = $picture->getFile()->getPathname();
        return $this->calculateDominantColors($pathToScript, $pathToPicture);
    }

    /**
     * Analysing the dominant colors of the picture. Original bash script for color analysis by Javier López
     * @link http://javier.io/blog/en/2015/09/30/using-imagemagick-and-kmeans-to-find-dominant-colors-in-images.html
     *
     * @return string[]
     */
    private function calculateDominantColors(string $pathToScript, string $pathToPicture): array
    {
        $command = sprintf(
            'bash %s -r 50x50 -f hex -k 6 %s',
            $pathToScript,
            $pathToPicture
        );

        exec($command, $colors);

        return $colors;
    }

    private function buildPathToScript(): string
    {
        return Kernel::getBasePath() . 'bin' . DIRECTORY_SEPARATOR . 'dcolors.sh';
    }
}
