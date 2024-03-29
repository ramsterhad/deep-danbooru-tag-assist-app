<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Container;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class ContainerFactory
{
    private static ?ContainerFactory $instance = null;

    private static ContainerInterface $containerBuilder;

    public static function getInstance(): ContainerFactory
    {
        if (!ContainerFactory::$instance instanceof ContainerFactory) {
            ContainerFactory::$instance = new self();
            ContainerFactory::$containerBuilder = (new BootstrapContainer())->initialise();
        }
        return ContainerFactory::$instance;
    }

    public function getContainer(): ContainerInterface
    {
        return self::$containerBuilder;
    }

    private function __construct () {}

    private function __clone() {}
}
