<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag;


class TagCollection implements \Countable
{
    private array $tags = [];

    public function add(TagInterface $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * @return TagInterface[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function count(): int
    {
        return count($this->tags);
    }

    public function toString(): string
    {
        $tags = '';

        /** @var TagInterface $tag */
        foreach ($this->tags as $tag) {
            $tags .= $tag->getName() . ' ';
        }

        $tags = trim($tags);

        return $tags;
    }
}
