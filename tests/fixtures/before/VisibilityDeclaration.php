<?php

namespace Tests\Fixtures;

class VisibilityDeclaration
{
    const MY_CONST = 'value';

    public $oldStyleProperty = 'old';

    public $publicProperty = 'public';

    public function noVisibilityMethod()
    {
        return 'no visibility';
    }

    public function publicMethod()
    {
        return 'public';
    }
}
