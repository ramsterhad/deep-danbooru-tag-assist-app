<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\Picture;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Infrastructure\DominantColorsRepository;

class DominantColorsService
{
    private DominantColorsRepository $dominantColorsRepository;

    public function __construct(DominantColorsRepository $dominantColorsRepository)
    {
        $this->dominantColorsRepository = $dominantColorsRepository;
    }

    public function calculateDominantColors(Post $post)
    {
        return $this->dominantColorsRepository->getDominantColors(
            $post->getPicture()
        );
    }
}
