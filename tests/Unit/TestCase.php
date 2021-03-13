<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit;


class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @link https://stackoverflow.com/a/2798203
     *
     * @param string $class
     * @param string $name
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected static function getMethod(string $class, string $name): \ReflectionMethod
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}