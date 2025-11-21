<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class YodaConditions
{
    public function checkValue($value): bool
    {
        if ($value === null) {
            return false;
        }

        if ($value == 'test') {
            return true;
        }

        if ($value === 42) {
            return true;
        }

        return false;
    }
}
