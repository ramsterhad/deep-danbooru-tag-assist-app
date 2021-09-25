<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\EndpointException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

class Endpoint
{
    public function requestPost(string $url, string $username, string $apiKey): string
    {
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = \curl_exec($ch);
        \curl_close($ch);

        if ($response === false) {
            throw new EndpointException();
        }

        return $response;
    }

    public function pushTags(
        string $url,
        string $username,
        string $apiKey,
        int $id,
        TagCollection $collection
    ): string {

        $apiRequestUrl = \sprintf('%sposts/%s.json', $url, $id);

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $apiRequestUrl);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        \curl_setopt($ch, CURLOPT_POSTFIELDS, ['post[tag_string]' => $collection->toString()]); // ['post[tag_string]' => $collection->toString()]
        \curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $response = \curl_exec($ch);
        \curl_close($ch);

        if ($response === false) {
            throw new EndpointException();
        }

        return $response;
    }
}