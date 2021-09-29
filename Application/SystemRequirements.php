<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

class SystemRequirements
{
    public function checkRequirementsForPictureHandling()
    {
        /*
        if (!is_dir(Picture::getStoragePath())) {

            // Try to create the directory.
            if (!mkdir(Picture::getStoragePath())) {
                throw new \Exception(
                    sprintf(
                        'Directory %s is missing. It should be right next to the file index.php.',
                        Config::get('picture_storage')
                    )
                );
            }
        }

        if (!is_writeable(Picture::getStoragePath())) {
            throw new \Exception(
                sprintf(
                    'Directory %s is not writeable! Check the permissions',
                    Config::get('picture_storage')
                )
            );
        }
        */
    }
}
