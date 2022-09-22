<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;

use Ramsterhad\DeepDanbooruTagAssist\Application\Kernel;

require_once '../bootstrap.php';
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'vendor/autoload.php';

$app = Kernel::getInstance();
$app->run();
