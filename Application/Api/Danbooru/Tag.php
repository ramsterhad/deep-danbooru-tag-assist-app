<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru;


class Tag extends \Ramsterhad\DeepDanbooruTagAssist\Application\Api\Tag\Tag
{
    const DANBOORU_TAG_HEXCOLOR_GENERAL = '#0075f8';
    const DANBOORU_TAG_HEXCOLOR_CHARACTER = '#00ab2c';
    const DANBOORU_TAG_HEXCOLOR_COPYRIGHT = '#a800aa';
    const DANBOORU_TAG_HEXCOLOR_ARTIST = '#c00004';
    const DANBOORU_TAG_HEXCOLOR_META = '#fd9200';

    private string $hexColor;


    public function __construct(string $name, string $score, $hexColor)
    {
        parent::__construct($name, $score);
        $this->hexColor = $hexColor;
    }


    public function getHexColor(): string
    {
        return $this->hexColor;
    }

    public function setHexColor(string $hexColor): void
    {
        $this->hexColor = $hexColor;
    }
}
