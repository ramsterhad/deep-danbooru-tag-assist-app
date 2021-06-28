<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Controller;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Endpoint;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\StatisticLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Controller\Contract\Controller;

use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Session;

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

        $this->logStatistics($id, $markedTags);

        Router::route('/');
    }

    /**
     * Writes the log files with the following data: <date> <time> <username> <id> <added tags>
     * @param array $markedTags
     */
    private function logStatistics(int $id, array $markedTags): void
    {
        $statistic = \sprintf(
            '%s %s %s',
            Session::get('username'),
            $id,
            \implode(',', $markedTags)
        );

        (new StatisticLogger())->log($statistic);
    }
}
