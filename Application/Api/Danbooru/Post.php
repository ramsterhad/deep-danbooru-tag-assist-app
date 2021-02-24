<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Collection;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag;

class Post
{
    private string $id;

    private string $picPreview;
    private string $picOriginal;
    private ?string $picLarge = null;
    private Collection $collection;

    public function __construct(
        string $id,
        string $picPreview,
        string $picOriginal,
        Collection $collection,
        ?string $picLarge
    ) {
        $this->id = $id;
        $this->picPreview = $picPreview;
        $this->picOriginal = $picOriginal;
        $this->collection = $collection;
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

    public function getTagCollection(): Collection
    {
        return $this->collection;
    }

    public static function convertDanbooruTagStringToCollection(string $tags): Collection
    {
        $tags = preg_split('/ /', $tags);
        $collection = new Collection();

        foreach ($tags as $item) {
            $collection->add(new Tag($item, '0.0'));
        }

        return $collection;
    }
}