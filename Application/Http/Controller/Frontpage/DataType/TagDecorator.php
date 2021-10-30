<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Http\Controller\Frontpage\DataType;


use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\TagInterface;

class TagDecorator implements TagInterface
{
    private TagInterface $apiTag;

    private bool $hightlightColoredTag = false;

    public function __construct(TagInterface $apiTag)
    {
        $this->apiTag = $apiTag;
    }

    public function getName(): string
    {
        return $this->apiTag->getName();
    }

    public function getScore(): string
    {
        return $this->apiTag->getScore();
    }

    public function getColor(): string
    {
        return $this->apiTag->getColor();
    }

    public function isColored(): bool
    {
        return $this->apiTag->isColored();
    }

    public function getNameWithoutColor(): string
    {
        return $this->apiTag->getNameWithoutColor();
    }

    public function getHexColor(): string
    {
        return $this->apiTag->getHexColor();
    }

    public function setHightlightColoredTag(bool $hightlightColoredTag): void
    {
        $this->hightlightColoredTag = $hightlightColoredTag;
    }

    public function highlightColoredTag(): bool
    {
        return $this->hightlightColoredTag;
    }

    public function setScore(string $score): TagInterface
    {
        $this->apiTag->setScore($score);
        return $this;
    }

    public function setColor(string $color): TagInterface
    {
        $this->apiTag->setColor($color);
        return $this;
    }

    public function setIsColored(bool $isColored): void
    {
        $this->apiTag->setIsColored($isColored);
    }
}
