<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Application;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\DotEnv\Config;

class Picture
{
    private string $url;
    private string $fullPathToFile;
    private ?\SplFileObject $file;
    private array $dominantColors = [];

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->file = new \SplFileObject($url);
        $this->fullPathToFile = self::getStoragePath() . $this->file->getFilename();
    }

    public function download(): bool
    {
        $ch = \curl_init($this->url);
        $fp = \fopen($this->fullPathToFile, 'w');

        \curl_setopt($ch, CURLOPT_URL, $this->url);
        \curl_setopt($ch, CURLOPT_VERBOSE, 1);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        \curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($ch, CURLOPT_HEADER, 0);

        $return = \curl_exec($ch);
        $hasWroteFile = \fwrite($fp, $return);

        \fclose($fp);
        \curl_close($ch);

        return (bool) $return && $hasWroteFile;
    }

    public function delete(): bool
    {
        // https://www.php.net/manual/de/class.splfileobject.php#113149
        $this->file = null;
        return \unlink($this->fullPathToFile);
    }

    /**
     * Returns the path with trailing slash.
     *
     * @return string
     * @throws \Exception
     */
    public static function getStoragePath(): string
    {
        return Application::getBasePath() . Config::get('picture_storage') . DIRECTORY_SEPARATOR;
    }

    public function getFullPathToFile(): string
    {
        return $this->fullPathToFile;
    }

    /**
     * Analysing the dominant colors of the picture. Original bash script for color analysis by Javier López
     * @link http://javier.io/blog/en/2015/09/30/using-imagemagick-and-kmeans-to-find-dominant-colors-in-images.html
     */
    public function calculateDominantColors(): void
    {
        $pathToFile = \sprintf('%s', Application::getBasePath() . 'bin' . DIRECTORY_SEPARATOR . 'dcolors.sh');
        \exec('bash ' . $pathToFile . ' -r 50x50 -f hex -k 6 ' . $this->getFullPathToFile(), $colors);
        $this->dominantColors = $colors;
    }

    /**
     * @return string[]
     */
    public function getDominantColors(): array
    {
        return $this->dominantColors;
    }

    public function setDominantColors(array $dominantColors): void
    {
        $this->dominantColors = $dominantColors;
    }
}