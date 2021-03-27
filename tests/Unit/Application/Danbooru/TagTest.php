<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\Application\Danbooru;



use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\TestCase;

class TagTest extends TestCase
{
    public function testSetHexColor(): void
    {
        $tag = new Tag('name', 'score', 'hex');
        $this->assertEquals('hex', $tag->getHexColor());

        $tag->setHexColor('hex2');
        $this->assertEquals('hex2', $tag->getHexColor());
    }
}