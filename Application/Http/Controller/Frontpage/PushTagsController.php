<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\PushTagsService;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\ControllerInterface;
use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\StatisticLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Session;

use function implode;
use function sprintf;

class PushTagsController implements ControllerInterface
{
    private PushTagsService $pushTagsService;

    public function __construct(PushTagsService $pushTagsService)
    {
        $this->pushTagsService = $pushTagsService;
    }

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

        /** @var PushTagsService $pushTagsService */
        $this->pushTagsService->pushTags(
            Config::get('danbooru_api_url'),
            Session::get('username'),
            Session::get('api_key'),
            $id,
            $collection
        );

        $this->logStatistics($id, $markedTags);

        Router::route('/');
    }

    /**
     * Writes the log files with the following data: <date> <time> <username> <id> <added tags>
     */
    private function logStatistics(int $id, array $markedTags): void
    {
        $statistic = sprintf(
            '%s %s %s',
            Session::get('username'),
            $id,
            implode(chr(9), $markedTags)
        );

        (new StatisticLogger())->log($statistic);
    }
}
