<?php

namespace Tests\Fixtures;

class VisibilityDeclaration
{
    const MY_CONST = 'value';

    var $oldStyleProperty = 'old';

    public $publicProperty = 'public';

    function noVisibilityMethod()
    {
        return 'no visibility';
    }

    public function publicMethod()
    {
        return 'public';
    }
}
