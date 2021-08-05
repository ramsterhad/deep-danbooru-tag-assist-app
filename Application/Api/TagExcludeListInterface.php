<?php


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api;


interface TagExcludeListInterface
{
    /**
     * @return string[]
     */
    public function getList(): array;
}
