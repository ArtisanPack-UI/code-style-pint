---
title: ArtisanPack UI Code Style Pint
---

# ArtisanPack UI Code Style Pint

Welcome to the ArtisanPack UI Code Style Pint documentation. This package provides a Laravel Pint configuration that enforces the ArtisanPack UI coding standards.

## Introduction

ArtisanPack UI Code Style Pint is a Laravel Pint preset that mirrors the coding standards defined in the `artisanpack-ui/code-style` PHPCS package. It allows developers to use Laravel Pint (which uses PHP-CS-Fixer under the hood) to automatically format code according to ArtisanPack UI standards.

### Key Features and Benefits

- **Pre-configured Pint Configuration**: Ready-to-use `pint.json` with all ArtisanPack UI rules
- **Artisan Command**: Publish configuration with `php artisan artisanpack:publish-pint-config`
- **Programmatic Configuration**: Build custom configurations with the fluent `PintConfigBuilder` API
- **Rule Group Toggles**: Enable/disable formatting, code structure, or best practice rules independently
- **Comprehensive Documentation**: Detailed guides for customization, IDE integration, and CI/CD

### Technology Stack

- **PHP 8.2+**: Built for modern PHP versions
- **Laravel 10/11/12**: Compatible with recent Laravel versions
- **Laravel Pint**: Uses Laravel's official code style tool
- **PHP-CS-Fixer**: Powered by PHP-CS-Fixer under the hood

## Getting Started

### For Laravel Applications

```bash
# Install the package
composer require artisanpack-ui/code-style-pint --dev

# Publish the configuration
php artisan artisanpack:publish-pint-config

# Run Pint
./vendor/bin/pint
```

### For Laravel Packages

When developing a Laravel package, use the `PintConfigBuilder` directly since `php artisan` may not be available:

```bash
# Install the package
composer require artisanpack-ui/code-style-pint --dev
```

Then create a script (e.g., `pint-setup.php`) in your package root:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');

echo "pint.json created successfully!\n";
```

Run it with:

```bash
php pint-setup.php
./vendor/bin/pint
```

For detailed instructions, see the [Customization Guide](customization).

### Basic Usage Example

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

// Generate a custom configuration
PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->removeRule('yoda_style')
    ->exclude('app/Legacy')
    ->save(__DIR__ . '/pint.json');
```

## Documentation

### Guides

- [Customization Guide](customization) - Customize rules and build configurations programmatically
- [Migration Guide](migration) - Migrate from other code style tools

### Reference

- [Rules Mapping](rules-mapping) - Complete PHPCS to Pint rule mapping reference

### Integration

- [IDE Integration](ide-integration) - Set up Pint in PhpStorm, VS Code, Vim, and more
- [CI/CD Integration](ci-cd) - Integrate with GitHub Actions, GitLab CI, and other platforms

## Complementary Tools

For complete code style enforcement, use this package alongside `artisanpack-ui/code-style` (PHPCS):

```json
{
  "require-dev": {
    "artisanpack-ui/code-style": "^1.0",
    "artisanpack-ui/code-style-pint": "^1.0"
  }
}
```

### Recommended Workflow

1. Run Pint to auto-fix formatting issues
2. Run PHPCS to catch remaining issues (security, naming conventions, etc.)

```bash
./vendor/bin/pint
./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
```

## Resources

- **Repository**: [GitLab Repository](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/code-style-pint)
- **Issues**: [GitLab Issues](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/code-style-pint/-/issues)
- **Support**: For support, please open an issue on GitLab or contact the maintainer at [me@jacobmartella.com](mailto:me@jacobmartella.com)
