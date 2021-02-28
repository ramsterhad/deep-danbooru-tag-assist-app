<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

class Post
{
    private string $id;

    private string $picPreview;
    private string $picOriginal;
    private ?string $picLarge = null;
    private TagCollection $tagCollection;

    public function __construct(
        string $id,
        string $picPreview,
        string $picOriginal,
        TagCollection $tags,
        ?string $picLarge
    ) {
        $this->id = $id;
        $this->picPreview = $picPreview;
        $this->picOriginal = $picOriginal;
        $this->tagCollection = $tags;
        $this->picLarge = $picLarge;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPicPreview(): string
    {
        return $this->picPreview;
    }

    public function getPicOriginal(): string
    {
        return $this->picOriginal;
    }

    public function getPicLarge(): ?string
    {
        return $this->picLarge;
    }

    public function getTags(): array
    {
        return $this->tagCollection->getTags();
    }

    public function getTagCollection(): TagCollection
    {
        return $this->tagCollection;
    }

    /**
     * @param string $tags
     * @param string $color
     * @param TagCollection $collection
     * @throws \Exception
     */
    public static function convertDanbooruTagsToTagCollection(string $tags, string $color, TagCollection $collection): void
    {
        if (strpos($color, '#') === false) {
            throw new \Exception('I need a hex color, nothing else!');
        }

        $tags = preg_split('/ /', $tags);

        foreach ($tags as $item) {
            $collection->add(new Tag($item, '0.0', $color));
        }
    }
}