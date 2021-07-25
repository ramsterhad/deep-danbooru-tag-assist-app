<?php

namespace Ramsterhad\DeepDanbooruTagAssist2\Test;

use Ramsterhad\DeepDanbooruTagAssist2\Bla\BlaInterface;

class Test implements TestInterface
{
    private $a;

    public function __construct(BlaInterface $bla)
    {
        $this->a = $bla;
    }

    public function hello(): void
    {
        echo 'hello';
        $this->a->world();
    }
}