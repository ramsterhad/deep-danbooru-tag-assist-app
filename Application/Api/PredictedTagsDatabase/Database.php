<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\DatabaseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Application;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;

class Database
{
    /**
     * Returns the result as a json string with the keys id and tags.
     * @param int $id
     * @return string
     * @throws DatabaseException
     */
    public function get(int $id): string
    {
        if ($id < 1 || $id > 2147483647) { // MySQL max signed integer.
            throw new DatabaseException('The id must be in range.', 100);
        }

        // Takes only the last 3 digits. (12345 -> 345)
        $filename = \substr(\strval($id), -3);

        if ($id < 10) {
            $filename = '00' . $id;
        } elseif ($id > 9 && $id < 100) {
            $filename = '0' . $id;
        }

        // /var/www/html/db/000.txt
        $targetFilename =  Application::getBasePath() . 'db/' . $filename . '.txt';

        // grep -rh "^12345:" /var/www/html/db/345.txt
        $line = \shell_exec(\sprintf('grep -rh "^%s:" %s', $id, $targetFilename));

        if ($line === null) {
            throw new DatabaseException('No data available. Probably an error. Please contact reiuyi.', 101);
        }

        // A post's data is separated by tabs
        $items = \preg_split('/\\t/', $line);

        // First position is always the id
        unset($items[0]);

        // No tags found
        if (count($items) < 1) {
            throw new DatabaseException('No data found for given Id. Probably an error. Please contact reiuyi.', 200);
        }

        $tags = [];
        foreach ($items as $item) {
            $scoreAndTag = \preg_split('/ /', $item); // Split score and tag: "(0.991) 1girl" becomes [0] => 0.991, [1] => 1girl
            $score = \str_replace(['(', ')'], '', $scoreAndTag[0]); // Remove the brackets from the score.
            $tag = \str_replace(["\n"], '', $scoreAndTag[1]); // Removes \n from the last tag (often rating:).
            $tags[] = [$score, $tag];
        }

        $output = [
            'id' => $id,
            'tags' => $tags
        ];

        return \stripslashes(\json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
}
