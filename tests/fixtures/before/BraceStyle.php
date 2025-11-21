<?php

namespace Tests\Fixtures;

class BraceStyle
{
    public function methodWithBadBraces()
    {
        if (true) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function anotherMethod()
    {
        foreach ([1, 2, 3] as $item) {
            echo $item;
        }
    }
}
