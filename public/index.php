<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;

use Ramsterhad\DeepDanbooruTagAssist\Application\Kernel;

require_once '../bootstrap.php';

$app = Kernel::getInstance();
$app->run();
