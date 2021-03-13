<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception;


class PostResponseException extends \Exception
{
    const CODE_INVALID_JSON = 100;
    const CODE_JSON_CONTAINS_NO_ITEM = 101;
    const CODE_JSON_CONTAINS_MORE_THAN_ONE_ITEM = 102;
    const CODE_JSON_ITEM_IS_NOT_OBJECT = 103;
    const CODE_JSON_ITEM_IS_MISSING_PROPERTIES = 104;
}