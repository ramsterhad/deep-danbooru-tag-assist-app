<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\Application\Router;


use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Config\RouterConfig;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;
use Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\ReflectionHelper;
use Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\TestCase;

class RouterTest extends TestCase
{
    public function testHasDefaultRoute(): void
    {
        $this->assertTrue(Router::getInstance()->hasRoute('_default'));
    }

    public function testReadControllerAndActionFromRequestGet(): void
    {
        $_GET['a'] = null;
        $_POST['r'] = 'b';

        $router = Router::getInstance();
        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);
        $router = Router::getInstance();

        $method = ReflectionHelper::getMethod(Router::class, 'readControllerAndActionFromRequest');
        $method->invoke($router);

        $property = ReflectionHelper::getProperty(Router::class, 'requestController');
        $value = $property->getValue($router);

        $this->assertEquals('a', $value);
    }

    public function testReadControllerAndActionFromRequestPost(): void
    {
        $_GET = [];
        $_POST['r'] = 'b';

        $router = Router::getInstance();
        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);
        $router = Router::getInstance();

        $method = ReflectionHelper::getMethod(Router::class, 'readControllerAndActionFromRequest');
        $method->invoke($router);

        $property = ReflectionHelper::getProperty(Router::class, 'requestController');
        $value = $property->getValue($router);

        $this->assertEquals('b', $value);
    }

    public function testReadControllerAndActionFromRequestDefault(): void
    {
        $_GET = [];
        $_POST = [];

        $router = Router::getInstance();
        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);
        $router = Router::getInstance();

        $method = ReflectionHelper::getMethod(Router::class, 'readControllerAndActionFromRequest');
        $method->invoke($router);

        $property = ReflectionHelper::getProperty(Router::class, 'requestController');
        $value = $property->getValue($router);

        $this->assertEquals('_default', $value);
    }

    public function testLoadRouterConfigObjectProbably(): void
    {
        $router = Router::getInstance();
        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);
        $router = Router::getInstance();

        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);

        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $value = $property->getValue($router);

        $this->assertNull($value);

        $router->loadRouterConfigObject();
        $value = $property->getValue($router);

        $this->assertInstanceOf(RouterConfig::class, $value);
    }

    public function testGetRouterConfigLoadObject(): void
    {
        $router = Router::getInstance();
        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $property->setValue($router, null);
        $router = Router::getInstance();

        $property = ReflectionHelper::getProperty(Router::class, 'routerConfig');
        $value = $property->getValue($router);

        $this->assertNull($value);

        $getter = $router->getRouterConfig();

        $this->assertInstanceOf(RouterConfig::class, $getter);
    }
}
