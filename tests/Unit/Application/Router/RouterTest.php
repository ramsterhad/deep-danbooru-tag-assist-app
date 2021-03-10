<?php

namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\Application\Router;


use PHPUnit\Framework\TestCase;
use Ramsterhad\DeepDanbooruTagAssist\Application\Router\Router;

class RouterTest extends TestCase
{
    public function testHasRoute(): void
    {
        $this->assertTrue(Router::getInstance()->hasRoute('/'));
        $this->assertFalse(Router::getInstance()->hasRoute('index.php'));
    }
}