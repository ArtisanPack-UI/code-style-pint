<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class BraceStyle
{
    public function methodWithBadBraces(): string
    {
        if (true) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function anotherMethod(): void
    {
        foreach ([1, 2, 3] as $item) {
            echo $item;
        }
    }
}
