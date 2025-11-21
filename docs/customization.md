---
title: Customization Guide
---

# Customization Guide

This guide explains how to customize the ArtisanPack UI Pint configuration for your specific needs.

## Using the PintConfigBuilder

The `PintConfigBuilder` class provides a fluent interface for building custom Pint configurations programmatically. This approach works in both Laravel applications and standalone Laravel packages.

### Basic Usage

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

$config = PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->toJson();

file_put_contents('pint.json', $config);
```

## Usage in Laravel Packages

When developing a Laravel package (not a full Laravel application), you won't have access to `php artisan`. Use `PintConfigBuilder` directly instead.

### Quick Setup Script

Create a `pint-setup.php` file in your package root:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__ . '/pint.json');

echo "pint.json created successfully!\n";
```

Run it once to generate your config:

```bash
php pint-setup.php
```

### Package-Specific Exclusions

Packages typically have different directory structures than applications:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->exclude([
        'vendor',
        'tests/fixtures',
        'workbench',
    ])
    ->save(__DIR__ . '/pint.json');
```

### Composer Script Integration

Add to your package's `composer.json`:

```json
{
  "scripts": {
    "pint:setup": "php pint-setup.php",
    "pint": "./vendor/bin/pint",
    "pint:test": "./vendor/bin/pint --test"
  }
}
```

Then run:

```bash
composer pint:setup  # Generate pint.json (first time only)
composer pint        # Format code
composer pint:test   # Check without fixing
```

### Alternative: Copy the Stub Directly

If you prefer not to use the builder, copy the stub file directly:

```bash
cp vendor/artisanpack-ui/code-style-pint/stubs/pint.json.stub pint.json
```

### Enabling/Disabling Rule Groups

You can selectively enable or disable rule groups:

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

// Only formatting rules, no best practices
$config = PintConfigBuilder::create()
    ->withFormattingRules(true)
    ->withCodeStructureRules(true)
    ->withBestPracticeRules(false)
    ->withArtisanPackUIPreset()
    ->toJson();
```

### Adding Custom Rules

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

$config = PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->addRule('concat_space', ['spacing' => 'one'])
    ->addRule('blank_line_before_statement', [
        'statements' => ['return', 'throw'],
    ])
    ->toJson();
```

### Removing Rules

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

$config = PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->removeRule('yoda_style')
    ->removeRule('declare_strict_types')
    ->toJson();
```

### Custom Exclusions

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

$config = PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->exclude('app/Legacy')
    ->exclude(['tests/fixtures', 'stubs'])
    ->toJson();
```

### Saving Configuration

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(base_path('pint.json'));
```

## Using the Preset Directly

You can also access the preset rules directly:

```php
use ArtisanPackUI\CodeStylePint\Presets\ArtisanPackUIPreset;

$preset = new ArtisanPackUIPreset();

// Get specific rule groups
$formattingRules = $preset->getFormattingRules();
$structureRules = $preset->getCodeStructureRules();
$bestPracticeRules = $preset->getBestPracticeRules();

// Get all rules
$allRules = $preset->getAllRules();

// Get default excludes
$excludes = $preset->getDefaultExcludes();
```

## Rule Groups

### Formatting Rules

Controls code formatting like indentation, spacing, and brace placement:

| Rule | Description |
|------|-------------|
| `binary_operator_spaces` | Controls spacing around operators, aligns assignments |
| `blank_line_after_opening_tag` | Blank line after `<?php` |
| `braces_position` | Brace placement for control structures and functions |
| `class_attributes_separation` | Blank lines between class elements |
| `control_structure_braces` | Ensures control structures use braces |
| `control_structure_continuation_position` | Position of else/catch |
| `function_declaration` | Spacing in function declarations |
| `multiline_whitespace_before_semicolons` | No multiline before semicolons |
| `no_extra_blank_lines` | Removes extra blank lines |
| `return_type_declaration` | Spacing around return types |
| `trailing_comma_in_multiline` | Trailing commas in multiline constructs |

### Code Structure Rules

Controls code organization:

| Rule | Description |
|------|-------------|
| `array_syntax` | Short array syntax (`[]`) |
| `global_namespace_import` | Import global classes/functions/constants |
| `no_unused_imports` | Remove unused imports |
| `ordered_class_elements` | Order class elements consistently |
| `ordered_imports` | Alphabetically sort imports |
| `ordered_traits` | Alphabetically sort traits |
| `single_class_element_per_statement` | One property/constant per statement |
| `single_trait_insert_per_statement` | One trait per use statement |
| `visibility_required` | Require visibility on all elements |

### Best Practice Rules

Enforces coding best practices:

| Rule | Description |
|------|-------------|
| `declare_strict_types` | Add `declare(strict_types=1)` |
| `fully_qualified_strict_types` | Convert FQCN to short names |
| `phpdoc_order` | Consistent PHPDoc tag order |
| `phpdoc_separation` | Separate PHPDoc tag groups |
| `phpdoc_types_order` | Order types with null last |
| `single_quote` | Single quotes for simple strings |
| `void_return` | Add void return type |
| `yoda_style` | Literals on left side of comparisons |

## Manual pint.json Customization

You can also manually edit the published `pint.json` file. Here are common customizations:

### Disable Yoda Style

```json
{
  "rules": {
    "yoda_style": false
  }
}
```

### Disable Strict Types

```json
{
  "rules": {
    "declare_strict_types": false
  }
}
```

### Change Assignment Alignment

```json
{
  "rules": {
    "binary_operator_spaces": {
      "default": "single_space",
      "operators": {
        "=": "single_space",
        "=>": "single_space"
      }
    }
  }
}
```

### Add Additional Exclusions

```json
{
  "exclude": [
    "node_modules",
    "vendor",
    "app/Legacy",
    "tests/fixtures"
  ]
}
```

## IDE Integration

For detailed IDE setup instructions, see [IDE Integration Guide](ide-integration).

### PhpStorm

1. Go to **Settings > Tools > External Tools**
2. Add a new tool:
   - Name: `Pint`
   - Program: `$ProjectFileDir$/vendor/bin/pint`
   - Arguments: `$FilePath$`
   - Working directory: `$ProjectFileDir$`

### VS Code

Install the "Laravel Pint" extension and configure:

```json
{
  "laravel-pint.enable": true,
  "laravel-pint.configPath": "pint.json"
}
```

## Related Documentation

- [Home](home)
- [Rules Mapping](rules-mapping)
- [IDE Integration](ide-integration)
- [Migration Guide](migration)
