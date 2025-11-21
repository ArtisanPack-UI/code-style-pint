<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class ArraySyntax
{
    public function getLongArraySyntax(): array
    {
        return ['foo', 'bar', 'baz'];
    }

    public function getAssociativeArray(): array
    {
        return ['foo' => 'bar', 'baz' => 'qux'];
    }

    public function getNestedArray(): array
    {
        return [
            'level1' => [
                'level2' => ['a', 'b', 'c'],
            ],
        ];
    }
}
