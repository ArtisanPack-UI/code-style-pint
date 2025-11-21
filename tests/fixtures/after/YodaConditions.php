<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class YodaConditions
{
    public function checkValue($value): bool
    {
        if (null === $value) {
            return false;
        }

        if ('test' == $value) {
            return true;
        }

        if (42 === $value) {
            return true;
        }

        return false;
    }
}
