<?php

namespace Tests\Fixtures;

class ArraySyntax
{
    public function getLongArraySyntax()
    {
        return array('foo', 'bar', 'baz');
    }

    public function getAssociativeArray()
    {
        return array('foo' => 'bar', 'baz' => 'qux');
    }

    public function getNestedArray()
    {
        return array(
            'level1' => array(
                'level2' => array('a', 'b', 'c')
            )
        );
    }
}
