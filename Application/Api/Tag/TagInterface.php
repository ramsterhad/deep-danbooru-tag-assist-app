<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag;

interface TagInterface
{
    public function getName(): string;

    public function setScore(string $score): self;

    public function getScore(): string;

    public function setColor(string $color): self;

    public function getColor(): string;

    public function setIsColored(bool $isColored): void;

    public function getNameWithoutColor(): string;
}
