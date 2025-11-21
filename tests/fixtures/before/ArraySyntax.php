<?php

namespace Tests\Fixtures;

class ArraySyntax
{
    public function getLongArraySyntax()
    {
        return ['foo', 'bar', 'baz'];
    }

    public function getAssociativeArray()
    {
        return ['foo' => 'bar', 'baz' => 'qux'];
    }

    public function getNestedArray()
    {
        return [
            'level1' => [
                'level2' => ['a', 'b', 'c'],
            ],
        ];
    }
}
