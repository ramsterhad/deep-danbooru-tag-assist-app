<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;

use Symfony\Component\Dotenv\Dotenv;
set_time_limit (3);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);


$dotenv = new Dotenv();
$dotenv->loadEnv(BASE_PATH . '.env');
