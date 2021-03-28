<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Endpoint;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\AuthenticationError;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Exception\PostResponseException;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Post;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Tag;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\ReflectionHelper;
use Ramsterhad\DeepDanbooruTagAssist\Tests\Unit\TestCase;

class DanbooruTest extends TestCase
{
    public function testRequestTagsExpectExceptionForInvalidJson(): void
    {
        $this->expectException(PostResponseException::class);
        $this->expectExceptionCode(PostResponseException::CODE_INVALID_JSON);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn('invalid json')
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testRequestTagsExpectExceptionForJsonIsNotAnArray(): void
    {
        $this->expectException(\TypeError::class);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn('{}')
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testRequestTagsExpectExceptionForHaveMoreThanOneArrayItem(): void
    {
        $this->expectException(PostResponseException::class);
        $this->expectExceptionCode(PostResponseException::CODE_JSON_CONTAINS_MORE_THAN_ONE_ITEM);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn('[{},{}]')
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testRequestTagsExpectExceptionForHaveAnEmptyArray(): void
    {
        $this->expectException(PostResponseException::class);
        $this->expectExceptionCode(PostResponseException::CODE_JSON_CONTAINS_NO_ITEM);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn('[]')
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testRequestTagsExpectExceptionArrayItemIsNotAnObject(): void
    {
        $this->expectException(PostResponseException::class);
        $this->expectExceptionCode(PostResponseException::CODE_JSON_ITEM_IS_NOT_OBJECT);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn('[[]]')
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testListOfRequiredJsonPropertiesExpectExceptionForMissingProperty(): void
    {
        $this->expectException(PostResponseException::class);
        $this->expectExceptionCode(PostResponseException::CODE_JSON_ITEM_IS_MISSING_PROPERTIES);

        $responseWithMissingId = '
        [{
        "tag_string":"",
        "tag_string_general":"",
        "tag_string_character":"",
        "tag_string_copyright":"",
        "tag_string_artist":"",
        "tag_string_meta":"",
        "file_url":"https://testbooru.donmai.us/data/55e3e61e0ded670aa9796812d3552595.webm",
        "large_file_url":"https://testbooru.donmai.us/data/55e3e61e0ded670aa9796812d3552595.webm",
        "preview_file_url":"https://testbooru.donmai.us/data/preview/55e3e61e0ded670aa9796812d3552595.jpg"
        }]';

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn($responseWithMissingId)
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);
    }

    public function testListOfRequiredJsonPropertiesHasExpectedProperties(): void
    {
        $danbooru = new Danbooru();
        $list = $danbooru->getListOfRequiredJsonPropertiesFromDanbooruResponse();

        $this->assertIsArray($list);

        $this->assertContains('id', $list);
        $this->assertContains('tag_string', $list);
        $this->assertContains('tag_string_general', $list);
        $this->assertContains('tag_string_character', $list);
        $this->assertContains('tag_string_copyright', $list);
        $this->assertContains('tag_string_artist', $list);
        $this->assertContains('tag_string_meta', $list);
        $this->assertContains('preview_file_url', $list);
        $this->assertContains('file_url', $list);
        $this->assertContains('large_file_url', $list);
    }

    public function testDanbooruReturnsNoTagsForRequest(): void
    {
        $responseWithEmptyTagEntries = '
        [{
        "id":63,
        "tag_string":"",
        "tag_string_general":"",
        "tag_string_character":"",
        "tag_string_copyright":"",
        "tag_string_artist":"",
        "tag_string_meta":"",
        "file_url":"https://testbooru.donmai.us/data/55e3e61e0ded670aa9796812d3552595.webm",
        "large_file_url":"https://testbooru.donmai.us/data/55e3e61e0ded670aa9796812d3552595.webm",
        "preview_file_url":"https://testbooru.donmai.us/data/preview/55e3e61e0ded670aa9796812d3552595.jpg"
        }]';

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('requestPost')
            ->willReturn($responseWithEmptyTagEntries)
        ;

        $danbooru = new Danbooru();
        $danbooru->requestTags(new TagCollection(), new Post(), $endpointMock);

        $this->assertEmpty($danbooru->getPost()->getTagCollection()->getTags());
        $this->assertEquals(0, $danbooru->getPost()->getTagCollection()->count());
    }

    public function testTransformJsonStringToObject(): void
    {
        $json = '[{"id":665,"tag_string":"foobar"}]';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformJsonStringToObject');
        $transformedObject = $method->invokeArgs(new Danbooru(), [$json])[0];

        $this->assertIsObject($transformedObject);

        $this->assertObjectHasAttribute('id', $transformedObject);
        $this->assertObjectHasAttribute('tag_string', $transformedObject);

        $this->assertEquals('665', $transformedObject->id);
        $this->assertEquals('foobar', $transformedObject->tag_string);

    }

    public function testExpectExceptionMissingArtistForTransformTagStringListsToCollection(): void
    {
        $this->expectException(PostResponseException::class);

        $stdObject = new \stdClass();
        //$stdObject->tag_string_artist = '';
        $stdObject->tag_string_copyright = '';
        $stdObject->tag_string_character = '';
        $stdObject->tag_string_general = '';
        $stdObject->tag_string_meta = '';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, new TagCollection()]);
    }

    public function testExpectExceptionMissingCopyrightForTransformTagStringListsToCollection(): void
    {
        $this->expectException(PostResponseException::class);

        $stdObject = new \stdClass();
        $stdObject->tag_string_artist = '';
        //$stdObject->tag_string_copyright = '';
        $stdObject->tag_string_character = '';
        $stdObject->tag_string_general = '';
        $stdObject->tag_string_meta = '';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, new TagCollection()]);
    }

    public function testExpectExceptionMissingCharacterForTransformTagStringListsToCollection(): void
    {
        $this->expectException(PostResponseException::class);

        $stdObject = new \stdClass();
        $stdObject->tag_string_artist = '';
        $stdObject->tag_string_copyright = '';
        //$stdObject->tag_string_character = '';
        $stdObject->tag_string_general = '';
        $stdObject->tag_string_meta = '';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, new TagCollection()]);
    }

    public function testExpectExceptionMissingGeneralForTransformTagStringListsToCollection(): void
    {
        $this->expectException(PostResponseException::class);

        $stdObject = new \stdClass();
        $stdObject->tag_string_artist = '';
        $stdObject->tag_string_copyright = '';
        $stdObject->tag_string_character = '';
        //$stdObject->tag_string_general = '';
        $stdObject->tag_string_meta = '';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, new TagCollection()]);
    }

    public function testExpectExceptionMissingMetaForTransformTagStringListsToCollection(): void
    {
        $this->expectException(PostResponseException::class);

        $stdObject = new \stdClass();
        $stdObject->tag_string_artist = '';
        $stdObject->tag_string_copyright = '';
        $stdObject->tag_string_character = '';
        $stdObject->tag_string_general = '';
        //$stdObject->tag_string_meta = '';

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, new TagCollection()]);
    }

    public function testTransformTagStringListsToCollection(): void
    {
        $stdObject = new \stdClass();
        $stdObject->tag_string_artist = 'artist';
        $stdObject->tag_string_copyright = 'copyright';
        $stdObject->tag_string_character = 'character';
        $stdObject->tag_string_general = 'general';
        $stdObject->tag_string_meta = 'meta';

        $collection = new TagCollection();

        $method = ReflectionHelper::getMethod(Danbooru::class, 'transformTagStringListsToCollection');
        $method->invokeArgs(new Danbooru(), [$stdObject, $collection]);

        $this->assertEquals($collection->getTags()[0]->getName(), 'artist');
        $this->assertEquals($collection->getTags()[0]->getScore(), '0.0');
        $this->assertEquals($collection->getTags()[0]->getHexColor(), '#c00004');

        $this->assertEquals($collection->getTags()[1]->getName(), 'copyright');
        $this->assertEquals($collection->getTags()[1]->getScore(), '0.0');
        $this->assertEquals($collection->getTags()[1]->getHexColor(), '#a800aa');

        $this->assertEquals($collection->getTags()[2]->getName(), 'character');
        $this->assertEquals($collection->getTags()[2]->getScore(), '0.0');
        $this->assertEquals($collection->getTags()[2]->getHexColor(), '#00ab2c');

        $this->assertEquals($collection->getTags()[3]->getName(), 'general');
        $this->assertEquals($collection->getTags()[3]->getScore(), '0.0');
        $this->assertEquals($collection->getTags()[3]->getHexColor(), '#0075f8');

        $this->assertEquals($collection->getTags()[4]->getName(), 'meta');
        $this->assertEquals($collection->getTags()[4]->getScore(), '0.0');
        $this->assertEquals($collection->getTags()[4]->getHexColor(), '#fd9200');
    }

    public function testConvertResponseObjectToPostObject(): void
    {
        $stdObject = new \stdClass();
        $stdObject->id = '665';
        $stdObject->preview_file_url = 'preview_file_url';
        $stdObject->file_url = 'file_url';
        $stdObject->large_file_url = 'large_file_url';

        $tag1 = new Tag('tag1', '0.0', '#000000');
        $tag2 = new Tag('tag2', '1.0', '#ffffff');

        $collection = new TagCollection();
        $collection->add($tag1);
        $collection->add($tag2);

        $post = new Post();

        $method = ReflectionHelper::getMethod(Danbooru::class, 'convertResponseObjectToPostObject');
        $method->invokeArgs(new Danbooru(), [$post, $stdObject, $collection]);

        $this->assertEquals('665', $post->getId());
        $this->assertEquals('preview_file_url', $post->getPicPreview());
        $this->assertEquals('file_url', $post->getPicOriginal());
        $this->assertEquals('large_file_url', $post->getPicLarge());

        $this->assertEquals($tag1, $post->getTags()[0]);
        $this->assertEquals($tag2, $post->getTags()[1]);
    }

    public function testEmptyTagForAddTagsFromResponseObjectDoesntAddToCollection(): void
    {
        $collection = new TagCollection();
        $method = ReflectionHelper::getMethod(Danbooru::class, 'addTagsFromResponseObjectToCollection');
        $method->invokeArgs(new Danbooru(), ['', '#000000', $collection]);
        $this->assertEquals(0, $collection->count());
    }

    public function testAddTagsFromResponseObjectAddsToCollection(): void
    {
        $collection = new TagCollection();
        $method = ReflectionHelper::getMethod(Danbooru::class, 'addTagsFromResponseObjectToCollection');
        $method->invokeArgs(new Danbooru(), ['tag1', '#000000', $collection]);
        $this->assertEquals(1, $collection->count());
        $this->assertEquals('tag1', $collection->getTags()[0]->getName());


        $collection = new TagCollection();
        $method = ReflectionHelper::getMethod(Danbooru::class, 'addTagsFromResponseObjectToCollection');
        $method->invokeArgs(new Danbooru(), ['tag1 tag2', '#000000', $collection]);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals('tag1', $collection->getTags()[0]->getName());
        $this->assertEquals('tag2', $collection->getTags()[1]->getName());


        $collection = new TagCollection();
        $method = ReflectionHelper::getMethod(Danbooru::class, 'addTagsFromResponseObjectToCollection');
        $method->invokeArgs(new Danbooru(), ['tag3(bracket) tag4_underscore', '#000000', $collection]);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals('tag3(bracket)', $collection->getTags()[0]->getName());
        $this->assertEquals('tag4_underscore', $collection->getTags()[1]->getName());
    }

    public function testExpectExceptionFromAddTagsFromResponseObjectForWrongColorFormat(): void
    {
        $this->expectException(\Exception::class);

        $method = ReflectionHelper::getMethod(Danbooru::class, 'addTagsFromResponseObjectToCollection');
        $method->invokeArgs(new Danbooru(), ['tag', 'wrongformat', new TagCollection()]);
    }

    public function testAuthenticateThrowsAuthenticationExceptionBecauseNoJsonWasReturned(): void
    {
        $this->expectException(AuthenticationError::class);
        $this->expectExceptionCode(AuthenticationError::CODE_RESPONSE_CONTAINED_INVALID_JSON);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn('invalid json')
        ;

        $danbooru = new Danbooru();
        $danbooru->authenticate($endpointMock, '', '');
    }

    public function testAuthenticateThrowsAuthenticationExceptionJsonCouldNotTransformedToAnObject(): void
    {
        $this->expectException(AuthenticationError::class);
        $this->expectExceptionCode(AuthenticationError::CODE_RESPONSE_MISSING_PROPERTIES);

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn('{}')
        ;

        $danbooru = new Danbooru();
        $danbooru->authenticate($endpointMock, '', '');
    }

    public function testAuthenticateReturnsTrueOnSuccess(): void
    {
        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn('{"id":1}')
        ;

        $danbooru = new Danbooru();
        $this->assertTrue($danbooru->authenticate($endpointMock, '', ''));
    }
}
