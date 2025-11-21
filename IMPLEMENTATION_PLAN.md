# ArtisanPack UI Code Style Pint - Implementation Plan

## Overview

This document outlines the plan to create a Laravel Pint configuration package that mirrors the coding standards defined in the `artisanpack-ui/code-style` PHPCS package. The goal is to allow developers to use Laravel Pint (which uses PHP-CS-Fixer under the hood) to format code in accordance with the ArtisanPack UI code style.

---

## Understanding the Source: code-style Package

The existing `code-style` package provides **17 custom PHPCS sniffs** organized into the following categories:

### 1. Formatting Sniffs
| Sniff | Description | Key Rules |
|-------|-------------|-----------|
| `IndentationSniff` | Enforces indentation with 4 spaces | Currently disabled in source |
| `BracesSniff` | Controls brace placement | Opening braces on same line for control structures, next line for functions |
| `SpacingSniff` | Enforces spacing around operators, parentheses, brackets | Space after commas, around operators, control structure keywords |
| `AlignmentSniff` | Aligns equal signs in consecutive assignments | Aligns `=` and `=>` operators in adjacent lines |
| `LineLengthSniff` | Enforces line length limits | 120 char limit (currently disabled in ruleset) |

### 2. Code Structure Sniffs
| Sniff | Description | Key Rules |
|-------|-------------|-----------|
| `ClassStructureSniff` | Enforces class structure | One class per file, traits at top, visibility required |
| `ControlStructuresSniff` | Controls format of control structures | Bracket format in PHP files, colon format in Blade |
| `ImportOrderingSniff` | Orders import statements | Classes → Functions → Constants |
| `ArraySyntaxSniff` | Enforces array formatting | Short syntax required, multi-item associative arrays on separate lines |

### 3. Naming Convention Sniffs
| Sniff | Description | Key Rules |
|-------|-------------|-----------|
| `NamingConventionsSniff` | Enforces naming patterns | Classes: PascalCase, Functions/Variables: camelCase, DB columns: snake_case |

### 4. Security Sniffs
| Sniff | Description | Key Rules |
|-------|-------------|-----------|
| `EscapeOutputSniff` | Ensures output escaping | All echo/print statements must use escape functions |
| `ValidatedSanitizedInputSniff` | Ensures input sanitization | Superglobals and inputs must be sanitized before DB operations |

### 5. Best Practice Sniffs
| Sniff | Description | Key Rules |
|-------|-------------|-----------|
| `YodaConditionalsSniff` | Enforces Yoda style comparisons | Literals on left side of comparisons |
| `DisallowedFunctionsSniff` | Bans certain functions | `die`, `exit`, `var_dump`, `print_r`, `create_function` |
| `TypeDeclarationSniff` | Requires type declarations | All parameters, properties, and return types |
| `PhpTagsSniff` | Controls PHP tag usage | Opening/closing tags on own lines, no PHP tags in Blade |
| `QuotesSniff` | Enforces quote usage | Single quotes unless escaping variables |

---

## Implementation Strategy

### Architecture Decision

Laravel Pint is a wrapper around PHP-CS-Fixer. The package should:

1. **Provide a preset configuration file** (`pint.json`) that can be published to projects
2. **Provide an Artisan command** to publish the configuration
3. **Provide a PHP class** that generates the configuration programmatically
4. **Include documentation** mapping PHPCS sniffs to Pint rules

### Package Structure

```
code-style-pint/
├── src/
│   ├── CodeStylePint.php              # Main class for config generation
│   ├── CodeStylePintServiceProvider.php
│   ├── Commands/
│   │   └── PublishPintConfigCommand.php
│   ├── Config/
│   │   ├── PintConfigBuilder.php      # Fluent builder for config
│   │   └── RuleMapper.php             # Maps PHPCS rules to Pint
│   └── Presets/
│       └── ArtisanPackUIPreset.php    # The main preset definition
├── config/
│   └── pint.json                      # Default Pint configuration
├── stubs/
│   └── pint.json.stub                 # Template for publishing
├── tests/
│   ├── Unit/
│   │   ├── ConfigBuilderTest.php
│   │   └── RuleMapperTest.php
│   └── Feature/
│       └── PintIntegrationTest.php
└── docs/
    ├── rules-mapping.md               # PHPCS to Pint rule mapping
    └── customization.md               # How to customize rules
```

---

## Rule Mapping: PHPCS Sniffs → PHP-CS-Fixer Rules

### Formatting Rules

| PHPCS Sniff | PHP-CS-Fixer Rule | Configuration |
|-------------|-------------------|---------------|
| `IndentationSniff` | `indentation_type` | `spaces` with 4 spaces |
| `BracesSniff` (control structures) | `braces_position` | `same_line` for control structures |
| `BracesSniff` (functions) | `braces_position` | `next_line_unless_newline_at_signature_end` for functions |
| `SpacingSniff` (operators) | `binary_operator_spaces` | `single_space` |
| `SpacingSniff` (commas) | `no_space_before_comma`, `space_after_comma` | Enable both |
| `SpacingSniff` (control structures) | `control_structure_braces`, `control_structure_continuation_position` | Configure spacing |
| `AlignmentSniff` | `binary_operator_spaces` | `align` for assignments |
| `LineLengthSniff` | N/A (Pint doesn't enforce line length) | Document as manual check |

### Code Structure Rules

| PHPCS Sniff | PHP-CS-Fixer Rule | Configuration |
|-------------|-------------------|---------------|
| `ClassStructureSniff` (one class per file) | `single_class_element_per_statement` | Enable |
| `ClassStructureSniff` (traits at top) | `ordered_traits` | Enable |
| `ClassStructureSniff` (visibility) | `visibility_required` | `['property', 'method']` |
| `ImportOrderingSniff` | `ordered_imports` | `alpha` with `class`, `function`, `const` order |
| `ArraySyntaxSniff` (short syntax) | `array_syntax` | `short` |
| `ArraySyntaxSniff` (multiline) | `array_indentation`, `multiline_array_trailing_comma` | Enable |
| `ControlStructuresSniff` | `braces` | Configure per file type (requires custom handling) |

### Naming Convention Rules

| PHPCS Sniff | PHP-CS-Fixer Rule | Configuration |
|-------------|-------------------|---------------|
| `NamingConventionsSniff` | N/A | Cannot be directly enforced by Pint; document as guidelines |

### Security Rules

| PHPCS Sniff | PHP-CS-Fixer Rule | Configuration |
|-------------|-------------------|---------------|
| `EscapeOutputSniff` | N/A | Cannot be enforced by Pint; requires PHPCS |
| `ValidatedSanitizedInputSniff` | N/A | Cannot be enforced by Pint; requires PHPCS |

### Best Practice Rules

| PHPCS Sniff | PHP-CS-Fixer Rule | Configuration |
|-------------|-------------------|---------------|
| `YodaConditionalsSniff` | `yoda_style` | `['equal' => true, 'identical' => true, 'less_and_greater' => false]` |
| `DisallowedFunctionsSniff` | N/A (partially via `native_function_invocation`) | Document as manual check |
| `TypeDeclarationSniff` | `declare_strict_types`, `void_return` | Enable type hints fixers |
| `PhpTagsSniff` | `blank_line_after_opening_tag`, `linebreak_after_opening_tag` | Enable |
| `QuotesSniff` | `single_quote` | Enable (won't change strings with variables) |

---

## Detailed Implementation Steps

### Step 1: Update Package Dependencies

Update `composer.json`:
```json
{
  "name": "artisanpack-ui/code-style-pint",
  "description": "Laravel Pint preset for ArtisanPack UI code standards",
  "type": "library",
  "require": {
    "php": "^8.2",
    "illuminate/support": "^10.0|^11.0|^12.0"
  },
  "require-dev": {
    "laravel/pint": "^1.0",
    "pestphp/pest": "^3.8"
  },
  "extra": {
    "laravel": {
      "providers": [
        "ArtisanPackUI\\CodeStylePint\\CodeStylePintServiceProvider"
      ]
    }
  }
}
```

### Step 2: Create the Pint Configuration File

Create `config/pint.json`:
```json
{
  "preset": "laravel",
  "rules": {
    "array_syntax": { "syntax": "short" },
    "binary_operator_spaces": {
      "default": "single_space",
      "operators": {
        "=": "align_single_space",
        "=>": "align_single_space"
      }
    },
    "blank_line_after_opening_tag": true,
    "braces_position": {
      "functions_opening_brace": "next_line_unless_newline_at_signature_end",
      "control_structures_opening_brace": "same_line"
    },
    "class_attributes_separation": {
      "elements": {
        "method": "one",
        "property": "one",
        "trait_import": "none"
      }
    },
    "control_structure_braces": true,
    "control_structure_continuation_position": {
      "position": "same_line"
    },
    "declare_strict_types": true,
    "fully_qualified_strict_types": true,
    "function_declaration": {
      "closure_function_spacing": "one"
    },
    "global_namespace_import": {
      "import_classes": true,
      "import_constants": true,
      "import_functions": true
    },
    "multiline_whitespace_before_semicolons": {
      "strategy": "no_multi_line"
    },
    "no_extra_blank_lines": {
      "tokens": [
        "extra",
        "throw",
        "use"
      ]
    },
    "no_unused_imports": true,
    "ordered_class_elements": {
      "order": [
        "use_trait",
        "constant_public",
        "constant_protected",
        "constant_private",
        "property_public",
        "property_protected",
        "property_private",
        "construct",
        "destruct",
        "magic",
        "phpunit",
        "method_public",
        "method_protected",
        "method_private"
      ]
    },
    "ordered_imports": {
      "sort_algorithm": "alpha",
      "imports_order": ["class", "function", "const"]
    },
    "ordered_traits": true,
    "phpdoc_order": true,
    "phpdoc_separation": true,
    "phpdoc_types_order": {
      "null_adjustment": "always_last"
    },
    "return_type_declaration": {
      "space_before": "none"
    },
    "single_class_element_per_statement": true,
    "single_quote": true,
    "single_trait_insert_per_statement": true,
    "trailing_comma_in_multiline": {
      "elements": ["arrays", "arguments", "parameters"]
    },
    "visibility_required": {
      "elements": ["property", "method", "const"]
    },
    "void_return": true,
    "yoda_style": {
      "equal": true,
      "identical": true,
      "less_and_greater": false
    }
  },
  "exclude": [
    "node_modules",
    "vendor",
    "bootstrap",
    "storage",
    "config",
    "database/migrations",
    "public",
    "resources/views"
  ]
}
```

### Step 3: Create Main Service Provider

Update `src/CodeStylePintServiceProvider.php` to:
- Register the publish command
- Publish the pint.json configuration
- Provide a method to programmatically generate config

### Step 4: Create Artisan Command

Create `src/Commands/PublishPintConfigCommand.php`:
- Publishes `pint.json` to project root
- Optionally merges with existing config
- Provides `--force` flag to overwrite

### Step 5: Create Configuration Builder

Create `src/Config/PintConfigBuilder.php`:
- Fluent interface for building Pint config
- Methods for each rule category
- `toArray()` and `toJson()` methods

### Step 6: Create Preset Class

Create `src/Presets/ArtisanPackUIPreset.php`:
- Defines all the rules
- Allows enabling/disabling specific rule groups
- Provides rule descriptions

### Step 7: Create Rule Mapper Documentation

Create `docs/rules-mapping.md`:
- Full mapping between PHPCS and Pint rules
- Notes on rules that cannot be translated
- Recommendations for complementary PHPCS usage

---

## Rules That Cannot Be Enforced by Pint

The following PHPCS sniffs have **no direct equivalent** in PHP-CS-Fixer/Pint and will need to remain as PHPCS-only checks:

### Security Rules (Critical)
1. **`EscapeOutputSniff`** - Validates escape function usage on output
2. **`ValidatedSanitizedInputSniff`** - Validates sanitization of superglobals

### Naming Conventions
3. **`NamingConventionsSniff`** - PascalCase/camelCase/snake_case enforcement
   - PHP-CS-Fixer doesn't rename variables or functions

### Custom Rules
4. **`DisallowedFunctionsSniff`** - Ban specific functions
   - Can be partially addressed with `native_function_invocation` but not comprehensively

### Line Length
5. **`LineLengthSniff`** - Maximum line length
   - PHP-CS-Fixer doesn't enforce line lengths

### Blade-Specific Rules
6. **`ControlStructuresSniff`** (Blade colon syntax)
7. **`PhpTagsSniff`** (no PHP tags in Blade)
   - These require Blade-aware tooling

**Recommendation**: Document that for complete code style enforcement, developers should use **both** `artisanpack-ui/code-style` (PHPCS) for detection and `artisanpack-ui/code-style-pint` (Pint) for automatic fixing.

---

## Testing Strategy

### Unit Tests
1. **ConfigBuilderTest** - Test configuration building
2. **RuleMapperTest** - Test rule mapping logic
3. **PresetTest** - Test preset generation

### Integration Tests
1. **Sample PHP files** with style violations
2. **Run Pint** with the configuration
3. **Assert** files are formatted correctly

### Test Files to Create
```
tests/
├── fixtures/
│   ├── before/
│   │   ├── ArraySyntax.php
│   │   ├── BraceStyle.php
│   │   ├── ImportOrder.php
│   │   ├── QuoteStyle.php
│   │   ├── YodaConditions.php
│   │   └── VisibilityDeclaration.php
│   └── after/
│       ├── ArraySyntax.php
│       ├── BraceStyle.php
│       └── ... (expected output)
├── Unit/
│   └── ...
└── Feature/
    └── PintIntegrationTest.php
```

---

## Documentation Structure

### README.md
- Package overview
- Installation instructions
- Quick start guide
- Link to full documentation

### docs/installation.md
- Composer installation
- Publishing configuration
- Verifying installation

### docs/usage.md
- Running Pint with the preset
- IDE integration
- CI/CD integration

### docs/rules-mapping.md
- Complete PHPCS → Pint mapping table
- Rules not available in Pint
- Complementary PHPCS usage

### docs/customization.md
- Overriding specific rules
- Creating custom presets
- Excluding files/directories

---

## Migration Guide for Users

For users migrating from PHPCS only to PHPCS + Pint:

### Recommended Setup

```json
// composer.json
{
  "require-dev": {
    "artisanpack-ui/code-style": "^1.0",
    "artisanpack-ui/code-style-pint": "^1.0"
  }
}
```

### Workflow

1. **Run Pint first** to auto-fix formatting issues:
   ```bash
   ./vendor/bin/pint
   ```

2. **Run PHPCS** to catch remaining issues (security, naming, etc.):
   ```bash
   ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
   ```

### CI/CD Pipeline Example

```yaml
# .github/workflows/code-style.yml
jobs:
  style:
    steps:
      - name: Run Pint
        run: ./vendor/bin/pint --test

      - name: Run PHPCS
        run: ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
```

---

## Timeline & Priorities

### Phase 1: Core Implementation (MVP)
- [ ] Update `composer.json` with correct dependencies
- [ ] Create base `pint.json` configuration
- [ ] Create `PublishPintConfigCommand`
- [ ] Update service provider
- [ ] Write basic documentation

### Phase 2: Enhanced Features
- [ ] Create `PintConfigBuilder` for programmatic config
- [ ] Create `ArtisanPackUIPreset` class
- [ ] Add rule group toggles (formatting, best-practices, etc.)
- [ ] Create customization documentation

### Phase 3: Testing & Documentation
- [ ] Write unit tests for config builder
- [ ] Write integration tests with fixture files
- [ ] Complete all documentation
- [ ] Create rules mapping documentation

### Phase 4: Polish
- [ ] Add IDE integration guides
- [ ] Add CI/CD examples
- [ ] Create migration guide
- [ ] Final review and release

---

## Open Questions

1. **Should the package require `laravel/pint`?**
   - Pro: Ensures Pint is available
   - Con: Users might already have Pint installed

2. **Should we support PHP-CS-Fixer directly?**
   - Pint is Laravel's wrapper, but some projects use PHP-CS-Fixer directly
   - Could provide both `pint.json` and `.php-cs-fixer.php`

3. **How to handle Blade files?**
   - Pint doesn't process Blade files
   - Document that Blade rules require PHPCS or a separate tool

4. **Should alignment rules be enabled by default?**
   - The `AlignmentSniff` aligns `=` and `=>` operators
   - This can be controversial and may not be everyone's preference

---

## Summary

This package will provide a Laravel Pint configuration that enforces approximately **70-80%** of the rules defined in the `artisanpack-ui/code-style` PHPCS package. The remaining rules (security, naming conventions, line length) cannot be enforced by PHP-CS-Fixer and will require continued use of PHPCS.

The package will be easy to install and configure, with sensible defaults and the ability to customize rules as needed. Clear documentation will help users understand which rules are enforced by Pint vs. PHPCS, and how to use both tools together for comprehensive code style enforcement.
