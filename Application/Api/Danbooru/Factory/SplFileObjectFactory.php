<?php

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\FileNotFoundException;
use SplFileObject;

class SplFileObjectFactory
{
    /**
     * @throws FileNotFoundException
     */
    public static function create(string $url): SplFileObject
    {
        if (!file_exists($url)) {
            throw new FileNotFoundException($url);
        }
        return new SplFileObject($url);
    }
}
