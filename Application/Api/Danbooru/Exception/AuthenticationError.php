<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception;

use Ramsterhad\DeepDanbooruTagAssist\Application\Exception\Exception;

class AuthenticationError extends Exception
{
    const CODE_RESPONSE_CONTAINED_INVALID_JSON = 100;
    const CODE_RESPONSE_MISSING_PROPERTIES = 101;
    const CODE_RESPONSE_INVALID_CREDENTIALS = 102;

    const MESSAGE_RESPONSE_INVALID_CREDENTIALS = 'Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.';
}
