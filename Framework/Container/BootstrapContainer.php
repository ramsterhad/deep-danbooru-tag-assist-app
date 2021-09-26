<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Framework\Container;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use function file_exists;
use function file_put_contents;

final class BootstrapContainer
{
    public function initialise(): ContainerInterface
    {
        $cache = __DIR__ .'/cache/container.php';

        if (file_exists($cache)) {
            require_once $cache;
            $container = new \ProjectServiceContainer();
        } else {
            $container = new ContainerBuilder();

            $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
            $loader->load('services.yaml');

            $container->compile();

            $dumper = new PhpDumper($container);
            file_put_contents($cache, $dumper->dump());
        }

        return $container;
    }
}
