<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint;

use ArtisanPackUI\CodeStylePint\Commands\PublishPintConfigCommand;
use Illuminate\Support\ServiceProvider;

class CodeStylePintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('codeStylePint', function ($app) {
            return new CodeStylePint;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishPintConfigCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../stubs/pint.json.stub' => base_path('pint.json'),
            ], 'artisanpack-pint-config');

            $this->publishes([
                __DIR__.'/../stubs/boost-pint-override.blade.php.stub' => base_path('.ai/guidelines/laravel/pint.blade.php'),
            ], 'artisanpack-boost-override');
        }
    }
}
