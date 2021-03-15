<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit;


class ReflectionHelper
{
    /**
     * @link https://stackoverflow.com/a/2798203
     *
     * @param string $class
     * @param string $name
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    public static function getMethod(string $class, string $name): \ReflectionMethod
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public static function getProperty(string $class, string $name): \ReflectionProperty
    {
        $class = new \ReflectionClass($class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        //var_dump($reflectionProperty->getValue(new Foo));
        return $property;
    }
}