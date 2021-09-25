<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Factory;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity\Post;

class PostFactory implements FactoryInterface
{

    /**
     * @return Post
     */
    public function create(): Post
    {
        return new Post();
    }
}