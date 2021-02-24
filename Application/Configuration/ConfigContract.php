<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Configuration;


interface ConfigContract
{
    public static function getInstance(): self;

    public static function get(string $name): string;

    public function has(string $name): bool;
}