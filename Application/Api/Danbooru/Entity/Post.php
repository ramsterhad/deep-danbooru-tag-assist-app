<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Entity;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\DataType\Picture;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagCollection;

class Post
{
    private string $id;

    private string $picPreview;
    private string $picOriginal;
    private ?string $picLarge = null;
    private TagCollection $tagCollection;

    private Picture $picture;
    private int $mediaAssetId;

    /**
     * @param string $id
     */
    public function setId(string $id): Post
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $picPreview
     */
    public function setPicPreview(string $picPreview): Post
    {
        $this->picPreview = $picPreview;
        return $this;
    }

    /**
     * @param string $picOriginal
     */
    public function setPicOriginal(string $picOriginal): Post
    {
        $this->picOriginal = $picOriginal;
        return $this;
    }

    /**
     * @param string|null $picLarge
     */
    public function setPicLarge(?string $picLarge): Post
    {
        $this->picLarge = $picLarge;
        return $this;
    }

    /**
     * @param TagCollection $tagCollection
     */
    public function setTagCollection(TagCollection $tagCollection): Post
    {
        $this->tagCollection = $tagCollection;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPicPreview(): string
    {
        return $this->picPreview;
    }

    /**
     * file_url  Link to original (full resolution) file
     * @see \Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Service\RequestPostService::request
     * @return string
     */
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

    public function getPicture(): Picture
    {
        return $this->picture;
    }

    public function setPicture(Picture $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getMediaAssetId(): int
    {
        return $this->mediaAssetId;
    }

    /**
     * @param int $mediaAssetId
     */
    public function setMediaAssetId(int $mediaAssetId): void
    {
        $this->mediaAssetId = $mediaAssetId;
    }


}