<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception;

class AuthenticationError extends \Exception
{
    const CODE_RESPONSE_CONTAINED_INVALID_JSON = 100;
    const CODE_RESPONSE_MISSING_PROPERTIES = 101;

}