<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class VisibilityDeclaration
{
    public const MY_CONST = 'value';

    public $oldStyleProperty = 'old';

    public $publicProperty = 'public';

    public function noVisibilityMethod(): string
    {
        return 'no visibility';
    }

    public function publicMethod(): string
    {
        return 'public';
    }
}
