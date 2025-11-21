# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
