<?php

namespace ArtisanPackUI\CodeStylePint\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ArtisanPackUI\CodeStylePint\A11y
 */
class CodeStylePint extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'codeStylePint';
	}
}
