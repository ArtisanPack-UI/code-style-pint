# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-11-23

### Added

- WordPress-style spacing support via custom PHP-CS-Fixer fixers
- `SpacesInsideParenthesisFixer` - Adds spaces inside parentheses (e.g., `if ( $var )`)
- `SpacesInsideBracketsFixer` - Adds spaces inside brackets for variable array indices (e.g., `$array[ $key ]`)
- `concat_space` rule for spacing around concatenation operators (e.g., `$a . $b`)
- `.php-cs-fixer.dist.php` configuration file with custom fixers
- `.php-cs-fixer.dist.php.stub` for publishing to user projects
- `--wordpress` flag for `artisan artisanpack:publish-pint-config` command
- WordPress spacing documentation in README for both Laravel applications and packages
- Package-specific WordPress spacing setup instructions
- Comprehensive WordPress spacing examples in Laravel Boost guidelines

### Changed

- Updated `PublishPintConfigCommand` to support WordPress-style spacing option
- Enhanced Laravel Boost AI guidelines with WordPress spacing instructions
- Updated README with WordPress-style spacing section
- Modified rule groups documentation to reflect concatenation spacing
- Updated composer scripts examples for both Pint and PHP-CS-Fixer workflows

### Removed

- `declare_strict_types` rule from default configuration (was too strict for some projects)
- Strict types mentions from documentation and guidelines

### Fixed

- Custom bracket fixer now correctly processes closing brackets by handling index shifts
- WordPress spacing correctly distinguishes between variable indices (`$array[ $key ]`) and literal indices (`$array['key']`)

## [1.0.0] - November 21, 2025

### Added

- Initial release of ArtisanPack UI Code Style Pint package
- Pre-configured `pint.json` with ArtisanPack UI coding standards
- `PublishPintConfigCommand` artisan command for publishing configuration
- `PintConfigBuilder` class for programmatic configuration generation
- `ArtisanPackUIPreset` class with organized rule groups
- Support for Laravel applications via artisan commands
- Support for Laravel packages via programmatic configuration
- Comprehensive documentation:
    - Customization guide with rule group toggles
    - Rules mapping between PHPCS and Pint
    - IDE integration guides (PhpStorm, VS Code, Vim, Emacs)
    - CI/CD integration examples (GitHub Actions, GitLab CI, Bitbucket, CircleCI, Azure DevOps, Jenkins)
    - Migration guide from various code style tools
- Laravel Boost AI guidelines integration
- Override guideline for replacing default Pint standards
- Complete test suite with 54 tests (Unit, Feature, Integration)
- GitLab CI/CD pipeline with automated releases

### Features

- Three rule groups: Formatting, Code Structure, Best Practices
- Aligned assignment operators (`=` and `=>`)
- Short array syntax enforcement
- Strict types declaration
- Alphabetically sorted imports
- Yoda style for equality comparisons
- Single quotes for simple strings
- Visibility required on all class elements
- Trailing commas in multiline constructs
- Comprehensive exclusion defaults (vendor, node_modules, storage, etc.)

### Compatibility

- PHP 8.2+
- Laravel 10.x, 11.x, 12.x
- Laravel Pint 1.x
- Complements `artisanpack-ui/code-style` PHPCS package
