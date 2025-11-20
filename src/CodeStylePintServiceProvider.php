<?php

namespace ArtisanPackUI\CodeStylePint;

use Illuminate\Support\ServiceProvider;

class CodeStylePintServiceProvider extends ServiceProvider
{

	public function register(): void
	{
		$this->app->singleton( 'codeStylePint', function ( $app ) {
			return new CodeStylePint();
		} );
	}
}
