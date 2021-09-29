<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;

// TODO: static!
class PostFactory
{

    /**
     * @return Post
     */
    public function create(): Post
    {
        return new Post();
    }
}