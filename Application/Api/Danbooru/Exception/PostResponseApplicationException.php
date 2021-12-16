<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception;


use Ramsterhad\DeepDanbooruTagAssist\Application\Logger\ErrorLogger;
use Ramsterhad\DeepDanbooruTagAssist\Application\Shared\Exception\ApplicationException;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Configuration\Service\ConfigurationInterface;
use Ramsterhad\DeepDanbooruTagAssist\Framework\Container\ContainerFactory;
use Throwable;

use function print_r;
use function sprintf;

class PostResponseApplicationException extends ApplicationException
{
    const CODE_INVALID_JSON = 100;
    const CODE_JSON_CONTAINS_NO_ITEM = 101;
    const CODE_JSON_CONTAINS_MORE_THAN_ONE_ITEM = 102;
    const CODE_JSON_ITEM_IS_NOT_OBJECT = 103;
    const CODE_JSON_ITEM_IS_MISSING_PROPERTIES = 104;
    const CODE_DANBOORU_ERROR_MESSAGE = 105;

    const MESSAGE_INVALID_JSON = 'Error! Return value has to be a valid JSON, but I got something... strange &#45576;_&#45576;.';
    const MESSAGE_JSON_CONTAINS_MORE_THAN_ONE_ITEM = 'Oh wow! Got way too much results! Pls check your API query. (&#180;&#65381;&#30410;&#65381;&#65344;*)';
    const MESSAGE_JSON_CONTAINS_NO_ITEM = 'Got nothing. &#175;\\_(&#12484;)_/&#175; Pls reload.';
    const MESSAGE_JSON_ITEM_IS_NOT_OBJECT = 'That\'s not an object. What. Is. This.?';
    const MESSAGE_JSON_ITEM_IS_MISSING_PROPERTIES = '( &#865;&#3232; &#662;&#815; &#865;&#3232;) Can\'t show you that. Maybe you don\'t have the permission to see the post?';

    public function __construct($response, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf("Response message: %s", $message);
        parent::__construct($message, $code, $previous);

        $configuration = ContainerFactory::getInstance()->getContainer()->get(ConfigurationInterface::class);

        if ($configuration->get('debug')) {
            (new ErrorLogger())->log(print_r($response, true));
        }
    }
}
