<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ArtisanPackUI\CodeStylePint\CodeStylePint
 */
class CodeStylePint extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'codeStylePint';
    }
}
