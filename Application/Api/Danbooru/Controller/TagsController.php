<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Collection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Controller;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class TagsController extends Controller
{
    public function pushNewTagsToDanbooru(): void
    {
        if (isset($_POST['tag_checkbox'])) {

            $id = (int) $_POST['tag_checkbox_post_id'];
            $collection = new Collection();

            foreach ($_POST['tag_checkbox_existing_tags'] as $tag) {
                $collection->add(new Tag($tag, '0.0'));
            }

            foreach ($_POST['tag_checkbox'] as $tag) {
                $collection->add(new Tag($tag, '0.0'));
            }

            $danbooru = new Danbooru('');
            $danbooru->pushTags($id, $collection);

            Router::route('/');
        }
    }
}