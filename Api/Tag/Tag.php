<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Api\Tag;


class Tag
{
    private string $name;
    private string $score;

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
}