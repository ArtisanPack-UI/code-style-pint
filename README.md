# ArtisanPack UI Code Style Pint

Laravel Pint preset for ArtisanPack UI code standards. This package provides a pre-configured `pint.json` file that enforces coding standards matching the `artisanpack-ui/code-style` PHPCS package.

## Installation

Install the package via Composer:

```bash
composer require artisanpack-ui/code-style-pint --dev
```

## Usage

### For Laravel Applications

Publish the `pint.json` configuration file to your project root:

```bash
php artisan artisanpack:publish-pint-config
```

Use `--force` to overwrite an existing `pint.json`:

```bash
php artisan artisanpack:publish-pint-config --force
```

Alternatively, use Laravel's standard publish command:

```bash
php artisan vendor:publish --tag=artisanpack-pint-config
```

### For Laravel Packages

When developing a Laravel package, use `PintConfigBuilder` directly since `php artisan` isn't available:

```php
<?php
// pint-setup.php
require __DIR__ . '/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');
```

Run it once to generate your config:

```bash
php pint-setup.php
```

Or copy the stub directly:

```bash
cp vendor/artisanpack-ui/code-style-pint/stubs/pint.json.stub pint.json
```

### Running Pint

Once the configuration is in place, run Pint to format your code:

```bash
./vendor/bin/pint
```

To test without making changes:

```bash
./vendor/bin/pint --test
```

## Programmatic Configuration

For advanced use cases, you can build configurations programmatically:

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

// Full preset (use base_path() in Laravel apps, __DIR__ in packages)
PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');

// Customize rule groups
PintConfigBuilder::create()
    ->withFormattingRules(true)
    ->withCodeStructureRules(true)
    ->withBestPracticeRules(false)  // Disable strict types, yoda style, etc.
    ->withArtisanPackUIPreset()
    ->addRule('concat_space', ['spacing' => 'one'])
    ->removeRule('yoda_style')
    ->exclude('tests/fixtures')
    ->save(__DIR__ . '/pint.json');
```

See [docs/customization.md](docs/customization.md) for detailed customization options.

## Rules Included

This preset enforces the following code style rules:

- **Array Syntax**: Short array syntax (`[]` instead of `array()`)
- **Binary Operator Spaces**: Single space around operators, aligned assignments
- **Brace Position**: Same line for control structures, next line for functions
- **Class Structure**: Ordered class elements (traits, constants, properties, methods)
- **Import Ordering**: Alphabetically sorted imports (classes, functions, constants)
- **Single Quotes**: Single quotes for strings without variables
- **Strict Types**: `declare(strict_types=1)` required
- **Trailing Commas**: In multiline arrays, arguments, and parameters
- **Visibility Required**: All properties, methods, and constants must have visibility
- **Yoda Style**: Literals on the left side of comparisons

## Complementary PHPCS Usage

Some rules cannot be enforced by Pint (security checks, naming conventions, line length). For complete code style enforcement, use both packages:

```json
{
  "require-dev": {
    "artisanpack-ui/code-style": "^1.0",
    "artisanpack-ui/code-style-pint": "^1.0"
  }
}
```

### Recommended Workflow

1. Run Pint first to auto-fix formatting:
   ```bash
   ./vendor/bin/pint
   ```

2. Run PHPCS to catch remaining issues:
   ```bash
   ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
   ```

## Documentation

- [Customization Guide](docs/customization.md) - Customize rules and configuration
- [Rules Mapping](docs/rules-mapping.md) - PHPCS to Pint rule mapping
- [IDE Integration](docs/ide-integration.md) - PhpStorm, VS Code, Vim, and more
- [CI/CD Integration](docs/ci-cd.md) - GitHub Actions, GitLab CI, and more
- [Migration Guide](docs/migration.md) - Migrate from other tools

## Contributing

As an open source project, this package is open to contributions from anyone. Please [read through the contributing guidelines](CONTRIBUTING.md) to learn more about how you can contribute to this project.

## License

This package is open-sourced software licensed under the [GPL-3.0-or-later license](LICENSE).
