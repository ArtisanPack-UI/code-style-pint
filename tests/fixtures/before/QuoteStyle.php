<?php

namespace Tests\Fixtures;

class QuoteStyle
{
    public function getStrings(): array
    {
        return [
            "simple string",
            "another string",
            "string with $variable interpolation",
            'already single quoted',
        ];
    }

    public function getKey(): string
    {
        return "key";
    }
}
