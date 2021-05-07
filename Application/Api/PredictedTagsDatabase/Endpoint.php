<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\PredictedTagsDatabase\Exception\EndpointException;

class Endpoint
{
    public function requestPredictedTags(string $url, string $id): string
    {
        $apiRequestUrl = \sprintf('%s?id=%s', $url, $id);

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $apiRequestUrl);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = \curl_exec($ch);
        \curl_close($ch);

        if ($response === false) {
            throw new EndpointException();
        }

        return $response;
    }
}
