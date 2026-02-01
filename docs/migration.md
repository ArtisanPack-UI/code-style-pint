---
title: Migration Guide
---

# Migration Guide

This guide helps you migrate to the ArtisanPack UI code style using Laravel Pint.

## From No Code Style Tool

If you're not currently using any code style tool:

### Step 1: Install the Package

```bash
composer require artisanpack-ui/code-style-pint --dev
```

### Step 2: Publish the Configuration

```bash
php artisan artisanpack:publish-pint-config
```

### Step 3: Run Pint on Your Codebase

First, see what changes Pint would make:

```bash
./vendor/bin/pint --test
```

Then apply the fixes:

```bash
./vendor/bin/pint
```

### Step 4: Review Changes

Review the changes before committing:

```bash
git diff
```

### Step 5: Commit in Stages (Recommended)

For large codebases, consider committing in stages:

```bash
# First commit: formatting changes only
./vendor/bin/pint
git add -A
git commit -m "style: apply ArtisanPack UI code style"
```

## From PHP-CS-Fixer

If you're migrating from PHP-CS-Fixer:

### Step 1: Install the Package

```bash
composer require artisanpack-ui/code-style-pint --dev
```

### Step 2: Backup Your Configuration

```bash
cp .php-cs-fixer.php .php-cs-fixer.php.backup
cp .php-cs-fixer.dist.php .php-cs-fixer.dist.php.backup  # if exists
```

### Step 3: Publish Pint Configuration

```bash
php artisan artisanpack:publish-pint-config
```

### Step 4: Compare Configurations

Review differences between your PHP-CS-Fixer config and the Pint preset. Key mappings:

| PHP-CS-Fixer | Pint (ArtisanPack UI) |
|--------------|----------------------|
| `@PSR12` | `preset: laravel` |
| `array_syntax` | `array_syntax` |
| `binary_operator_spaces` | `binary_operator_spaces` |
| `braces` | `braces_position` |
| `ordered_imports` | `ordered_imports` |
| `single_quote` | `single_quote` |
| `yoda_style` | `yoda_style` |

### Step 5: Migrate Custom Rules

If you have custom rules in `.php-cs-fixer.php`, add them to `pint.json`:

```php
// Old .php-cs-fixer.php
return (new PhpCsFixer\Config())
    ->setRules([
        'concat_space' => ['spacing' => 'one'],
        'blank_line_before_statement' => ['statements' => ['return']],
    ]);
```

```json
// New pint.json (add to existing rules)
{
  "rules": {
    "concat_space": { "spacing": "one" },
    "blank_line_before_statement": { "statements": ["return"] }
  }
}
```

### Step 6: Run Pint

```bash
./vendor/bin/pint
```

### Step 7: Remove PHP-CS-Fixer

```bash
composer remove friendsofphp/php-cs-fixer
rm .php-cs-fixer.php .php-cs-fixer.dist.php .php-cs-fixer.cache
```

## From Laravel's Default Pint

If you're using Laravel's default Pint configuration:

### Step 1: Install the Package

```bash
composer require artisanpack-ui/code-style-pint --dev
```

### Step 2: Backup Existing Configuration

```bash
cp pint.json pint.json.backup  # if exists
```

### Step 3: Publish ArtisanPack UI Configuration

```bash
php artisan artisanpack:publish-pint-config --force
```

### Step 4: Review Key Differences

The ArtisanPack UI preset differs from Laravel's default in these areas:

| Rule | Laravel Default | ArtisanPack UI |
|------|-----------------|----------------|
| `binary_operator_spaces` | `single_space` | Aligned `=` and `=>` |
| `braces_position` (functions) | `same_line` | `next_line_unless_newline_at_signature_end` |
| `yoda_style` | `false` | `true` for `equal` and `identical` |
| `declare_strict_types` | Not enforced | `true` |
| `void_return` | Not enforced | `true` |

### Step 5: Run Pint

```bash
./vendor/bin/pint
```

## From PHPCS Only

If you're using only `artisanpack-ui/code-style` (PHPCS):

### Step 1: Install Pint Package

```bash
composer require artisanpack-ui/code-style-pint --dev
```

### Step 2: Publish Configuration

```bash
php artisan artisanpack:publish-pint-config
```

### Step 3: Update Workflow

Change your workflow to run Pint before PHPCS:

```bash
# Auto-fix with Pint
./vendor/bin/pint

# Then check remaining issues with PHPCS
./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
```

### Step 4: Keep Both Packages

PHPCS is still needed for:
- Security checks (output escaping, input sanitization)
- Naming conventions
- Line length limits
- Disallowed functions
- Blade-specific rules

See [Rules Mapping](Rules-Mapping) for details on which rules require PHPCS.

## Gradual Migration

For large projects, consider a gradual migration:

### Option 1: Directory by Directory

```bash
# Week 1: Format app/Models
./vendor/bin/pint app/Models

# Week 2: Format app/Http/Controllers
./vendor/bin/pint app/Http/Controllers

# Week 3: Format remaining directories
./vendor/bin/pint
```

### Option 2: Rule by Rule

Start with a minimal configuration and add rules gradually:

```json
// Week 1: Basic formatting
{
  "preset": "laravel",
  "rules": {
    "array_syntax": { "syntax": "short" },
    "single_quote": true
  }
}
```

```json
// Week 2: Add structure rules
{
  "preset": "laravel",
  "rules": {
    "array_syntax": { "syntax": "short" },
    "single_quote": true,
    "ordered_imports": {
      "sort_algorithm": "alpha",
      "imports_order": ["class", "function", "const"]
    }
  }
}
```

```json
// Week 3: Full preset
// Run: php artisan artisanpack:publish-pint-config --force
```

### Option 3: Exclude Legacy Code

```json
{
  "preset": "laravel",
  "rules": { ... },
  "exclude": [
    "app/Legacy",
    "app/OldModules"
  ]
}
```

## Handling Large Changesets

### Create a Dedicated PR

1. Create a branch specifically for code style changes:

```bash
git checkout -b style/apply-artisanpack-ui-code-style
```

2. Run Pint:

```bash
./vendor/bin/pint
```

3. Commit and create PR:

```bash
git add -A
git commit -m "style: apply ArtisanPack UI code style"
git push origin style/apply-artisanpack-ui-code-style
```

4. In the PR description, note:
   - This is an automated code style change
   - No functional changes are included
   - Review can focus on verifying tests still pass

### Split by File Type

```bash
# Commit 1: Models
./vendor/bin/pint app/Models
git add app/Models && git commit -m "style: format Models"

# Commit 2: Controllers
./vendor/bin/pint app/Http/Controllers
git add app/Http/Controllers && git commit -m "style: format Controllers"

# Continue for other directories...
```

## Disabling Controversial Rules

If certain rules cause too many changes, disable them initially. See [Customization Guide](Customization) for details.

```php
use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->removeRule('yoda_style')           // Disable Yoda conditions
    ->removeRule('declare_strict_types') // Disable strict types
    ->addRule('binary_operator_spaces', [
        'default' => 'single_space',     // Disable alignment
    ])
    ->save(base_path('pint.json'));
```

## Verifying Migration

After migration, verify everything works:

```bash
# 1. Run tests
./vendor/bin/pest
# or
./vendor/bin/phpunit

# 2. Run Pint in test mode
./vendor/bin/pint --test

# 3. Run PHPCS
./vendor/bin/phpcs --standard=ArtisanPackUIStandard .

# 4. Run static analysis if available
./vendor/bin/phpstan analyse
```

## Rollback Plan

If issues arise:

```bash
# Restore backup
cp pint.json.backup pint.json

# Or reset changes
git checkout -- .

# Or revert commit
git revert HEAD
```

## Getting Help

If you encounter issues during migration:

1. Check the [Customization Guide](Customization) for rule adjustments
2. Review the [Rules Mapping](Rules-Mapping) for PHPCS equivalents
3. Open an issue on the package repository

## Related Documentation

- [Home](Home)
- [Customization Guide](Customization)
- [Rules Mapping](Rules-Mapping)
- [CI/CD Integration](Ci-Cd)
