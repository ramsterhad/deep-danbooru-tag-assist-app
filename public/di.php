<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;


use Ramsterhad\DeepDanbooruTagAssist\Application\Container\ContainerFactory;

require_once '../bootstrap.php';



/*
// creating an empty container builder

$containerBuilder = new ContainerBuilder();

// init yaml file loader
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));

// load services from the yaml file
$loader->load('services.yaml');

$containerBuilder->compile();


// fetch service from the service container
$serviceOne = $containerBuilder->get(TestInterface::class);
$serviceOne->hello();

*/

$container = ContainerFactory::getInstance()->getContainer();
dump($container);

/** @var TestInterface $service */
/*
$service = $container->get(TestInterface::class);
$service->hello();
*/
