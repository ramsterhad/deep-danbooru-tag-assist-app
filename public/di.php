<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;


use Ramsterhad\DeepDanbooruTagAssist\Application\Authentication\Authentication;
use Ramsterhad\DeepDanbooruTagAssist\Application\Container\ContainerFactory;

require_once '../bootstrap.php';


$container = ContainerFactory::getInstance()->getContainer();
dump($container);

/** @var Authentication $authentication */
$authentication = $container->get(Authentication::class);

