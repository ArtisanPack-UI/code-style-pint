<?php

declare(strict_types=1);

namespace Tests;

use ArtisanPackUI\CodeStylePint\CodeStylePintServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CodeStylePintServiceProvider::class,
        ];
    }
}
