<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage\Service;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage\DataType\TagDecorator;

class ConvertTagsToDecoratedTagsService
{
    public function convert(TagCollection $tagCollection): TagCollection
    {
        $decorated = new TagCollection();

        foreach ($tagCollection->getTags() as $tag) {
            $decorated->add(new TagDecorator($tag));
        }

        return $decorated;
    }
}
