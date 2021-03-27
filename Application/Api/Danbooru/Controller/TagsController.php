<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Endpoint;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;

use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class TagsController implements Controller
{
    public function pushNewTagsToDanbooru(): void
    {
        $id = (int) $_POST['tag_checkbox_post_id'] ?? 0;
        $existingTags = $_POST['tag_checkbox_existing_tags'] ?? [];
        $markedTags = $_POST['tag_checkbox'] ?? [];

        $collection = new TagCollection();

        foreach ($existingTags as $tag) {
            $collection->add(new Tag($tag, '0.0'));
        }

        foreach ($markedTags as $tag) {
            $collection->add(new Tag($tag, '0.0'));
        }

        $danbooru = new Danbooru();
        $danbooru->pushTags(
            new Endpoint(),
            $id,
            $collection
        );

        Router::route('/');
    }
}