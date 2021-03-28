<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;

use Ramsterhad\DeepDanbooruTagAssist\Application\Application;

require_once '../bootstrap.php';

$app = Application::getInstance();
$app->run();
