<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag;

use function str_contains;

class Tag implements TagInterface
{
    private string $name;
    private string $score;
    private string $color = '';
    private bool $isColored = false;

    public function __construct(string $name, string $score)
    {
        $this->name = $name;
        $this->score = $score;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setIsColored(bool $isColored): void
    {
        $this->isColored = $isColored;
    }

    public function isColored(): bool
    {
        return $this->isColored;
    }

    public function getNameWithoutColor(): string
    {
        // This respects that some colored tags have multiple underscores like 'light_blue_hair'
        return substr($this->name, strrpos($this->name, '_') + 1);
    }
}
