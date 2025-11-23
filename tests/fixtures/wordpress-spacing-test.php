<?php

namespace Tests\Fixtures;

class WordPressSpacingTest
{
    public function testParenthesesSpacing( $param1, $param2 ): string
    {
        if( 'test' === $param1 ) {
            return 'yes';
        }

        foreach( ['item1', 'item2'] as $item ) {
            echo $item;
        }

        $result = $this->helperMethod( $param1, $param2 );

        return $result;
    }

    public function testBracketSpacing(): void
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];

        // This should have spaces (variable index)
        $key   = 'key1';
        $value = $array[ $key ];

        // This should NOT have spaces (literal index)
        $literal = $array['key1'];
        $numeric = $array[0];
    }

    public function testConcatenation(): string
    {
        $first  = 'Hello';
        $second = 'World';

        return $first . ' ' . $second;
    }

    private function helperMethod( $arg1, $arg2 ): string
    {
        return $arg1 . ' - ' . $arg2;
    }
}
