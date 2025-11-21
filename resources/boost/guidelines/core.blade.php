## ArtisanPack UI Code Style Pint

This package provides a Laravel Pint configuration that enforces the ArtisanPack UI coding standards, mirroring the rules defined in the `artisanpack-ui/code-style` PHPCS package.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require artisanpack-ui/code-style-pint --dev
</code-snippet>
@endverbatim

### Setup for Laravel Applications

Publish the pint.json configuration file:

@verbatim
<code-snippet name="Publish Pint configuration" lang="bash">
php artisan artisanpack:publish-pint-config
</code-snippet>
@endverbatim

### Setup for Laravel Packages

When developing a Laravel package, use PintConfigBuilder directly:

@verbatim
<code-snippet name="Generate pint.json programmatically" lang="php">
<?php
// pint-setup.php
require __DIR__ . '/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');

echo "pint.json created successfully!\n";
</code-snippet>
@endverbatim

Run the setup script once:

@verbatim
<code-snippet name="Run setup script" lang="bash">
php pint-setup.php
</code-snippet>
@endverbatim

### Running Pint

Format your code:

@verbatim
<code-snippet name="Format code with Pint" lang="bash">
./vendor/bin/pint
</code-snippet>
@endverbatim

Test without making changes:

@verbatim
<code-snippet name="Test formatting" lang="bash">
./vendor/bin/pint --test
</code-snippet>
@endverbatim

### Programmatic Configuration

Customize the configuration using PintConfigBuilder:

@verbatim
<code-snippet name="Customize Pint configuration" lang="php">
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

// Full preset
PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');

// Customized preset
PintConfigBuilder::create()
    ->withFormattingRules(true)
    ->withCodeStructureRules(true)
    ->withBestPracticeRules(false)  // Disable strict types, yoda style
    ->withArtisanPackUIPreset()
    ->removeRule('yoda_style')
    ->addRule('concat_space', ['spacing' => 'one'])
    ->exclude('tests/fixtures')
    ->save(__DIR__ . '/pint.json');
</code-snippet>
@endverbatim

### Rule Groups

The preset includes three rule groups:

- **Formatting Rules**: Controls indentation, spacing, braces, alignment
- **Code Structure Rules**: Enforces array syntax, import ordering, class organization
- **Best Practice Rules**: Enforces strict types, single quotes, Yoda conditions, void returns

Each group can be enabled/disabled independently using `withFormattingRules()`, `withCodeStructureRules()`, and `withBestPracticeRules()`.

### Key Rules Enforced

- Short array syntax (`[]` instead of `array()`)
- Aligned assignment operators (`=` and `=>`)
- Single quotes for simple strings
- `declare(strict_types=1)` required
- Alphabetically sorted imports
- Yoda conditions for equality checks
- Trailing commas in multiline constructs
- Visibility required on all properties, methods, and constants

### Complementary Usage with PHPCS

For complete code style enforcement, use both Pint and PHPCS together:

@verbatim
<code-snippet name="Complete code style workflow" lang="bash">
# Auto-fix formatting with Pint
./vendor/bin/pint

# Check remaining issues with PHPCS
./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
</code-snippet>
@endverbatim

Pint handles auto-fixable formatting rules (~70%), while PHPCS catches security issues, naming conventions, and line length violations that Pint cannot enforce.

### Composer Scripts

Add these scripts to your composer.json for convenience:

@verbatim
<code-snippet name="Add composer scripts" lang="json">
{
  "scripts": {
    "pint": "./vendor/bin/pint",
    "pint:test": "./vendor/bin/pint --test",
    "style": [
      "@pint:test",
      "./vendor/bin/phpcs --standard=ArtisanPackUIStandard ."
    ]
  }
}
</code-snippet>
@endverbatim

### Best Practices

1. Always run Pint before committing code
2. Use `--test` flag in CI/CD pipelines to verify formatting
3. For large codebases, use `--dirty` to only format changed files
4. Combine with PHPCS for comprehensive code style enforcement
5. Use PintConfigBuilder for packages; use artisan command for applications
