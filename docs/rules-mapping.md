---
title: Rules Mapping - PHPCS to Pint
---

# Rules Mapping: PHPCS to Pint

This document provides a complete mapping between the PHPCS sniffs in `artisanpack-ui/code-style` and their equivalent PHP-CS-Fixer rules used by Laravel Pint.

## Overview

The ArtisanPack UI code style is enforced through 17 PHPCS sniffs. Of these:
- **~70%** can be enforced by Pint (auto-fixable)
- **~30%** require PHPCS for detection only

## Formatting Rules

| PHPCS Sniff | Pint Rule | Status | Notes |
|-------------|-----------|--------|-------|
| `IndentationSniff` | `indentation_type` | Supported | 4 spaces (currently disabled in source) |
| `BracesSniff` (control structures) | `braces_position` | Supported | `control_structures_opening_brace: same_line` |
| `BracesSniff` (functions) | `braces_position` | Supported | `functions_opening_brace: next_line_unless_newline_at_signature_end` |
| `SpacingSniff` (operators) | `binary_operator_spaces` | Supported | `default: single_space` |
| `SpacingSniff` (commas) | `no_space_before_comma`, `space_after_comma` | Supported | Included in Laravel preset |
| `SpacingSniff` (control structures) | `control_structure_continuation_position` | Supported | `position: same_line` |
| `AlignmentSniff` | `binary_operator_spaces` | Supported | `=` and `=>` use `align_single_space` |
| `LineLengthSniff` | N/A | Not Supported | Pint cannot enforce line length |

## Code Structure Rules

| PHPCS Sniff | Pint Rule | Status | Notes |
|-------------|-----------|--------|-------|
| `ClassStructureSniff` (one class per file) | `single_class_element_per_statement` | Partial | One property/constant per statement |
| `ClassStructureSniff` (traits at top) | `ordered_class_elements`, `ordered_traits` | Supported | Traits first in class order |
| `ClassStructureSniff` (visibility) | `visibility_required` | Supported | `property`, `method`, `const` |
| `ImportOrderingSniff` | `ordered_imports` | Supported | `alpha` sort, `class → function → const` |
| `ArraySyntaxSniff` (short syntax) | `array_syntax` | Supported | `syntax: short` |
| `ArraySyntaxSniff` (multiline) | `trailing_comma_in_multiline` | Supported | Arrays, arguments, parameters |
| `ControlStructuresSniff` (PHP files) | `control_structure_braces` | Supported | Braces required |
| `ControlStructuresSniff` (Blade files) | N/A | Not Supported | Pint doesn't process Blade |

## Naming Convention Rules

| PHPCS Sniff | Pint Rule | Status | Notes |
|-------------|-----------|--------|-------|
| `NamingConventionsSniff` (PascalCase classes) | N/A | Not Supported | Cannot rename identifiers |
| `NamingConventionsSniff` (camelCase methods) | N/A | Not Supported | Cannot rename identifiers |
| `NamingConventionsSniff` (snake_case DB) | N/A | Not Supported | Cannot rename identifiers |

## Security Rules

| PHPCS Sniff | Pint Rule | Status | Notes |
|-------------|-----------|--------|-------|
| `EscapeOutputSniff` | N/A | Not Supported | Cannot validate escape functions |
| `ValidatedSanitizedInputSniff` | N/A | Not Supported | Cannot validate sanitization |

## Best Practice Rules

| PHPCS Sniff | Pint Rule | Status | Notes |
|-------------|-----------|--------|-------|
| `YodaConditionalsSniff` | `yoda_style` | Supported | `equal: true`, `identical: true` |
| `DisallowedFunctionsSniff` | N/A | Not Supported | Cannot ban specific functions |
| `TypeDeclarationSniff` (strict types) | `declare_strict_types` | Supported | Adds declaration |
| `TypeDeclarationSniff` (void return) | `void_return` | Supported | Adds void return type |
| `TypeDeclarationSniff` (parameters) | N/A | Not Supported | Cannot infer types |
| `PhpTagsSniff` (opening tag) | `blank_line_after_opening_tag` | Supported | Blank line after `<?php` |
| `PhpTagsSniff` (Blade) | N/A | Not Supported | Pint doesn't process Blade |
| `QuotesSniff` | `single_quote` | Supported | Single quotes for simple strings |

## Pint Configuration

The following rules are configured in the ArtisanPack UI Pint preset:

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
      "elements": { "method": "one", "property": "one", "trait_import": "none" }
    },
    "control_structure_braces": true,
    "control_structure_continuation_position": { "position": "same_line" },
    "declare_strict_types": true,
    "fully_qualified_strict_types": true,
    "function_declaration": { "closure_function_spacing": "one" },
    "global_namespace_import": {
      "import_classes": true,
      "import_constants": true,
      "import_functions": true
    },
    "multiline_whitespace_before_semicolons": { "strategy": "no_multi_line" },
    "no_extra_blank_lines": { "tokens": ["extra", "throw", "use"] },
    "no_unused_imports": true,
    "ordered_class_elements": {
      "order": [
        "use_trait", "constant_public", "constant_protected", "constant_private",
        "property_public", "property_protected", "property_private",
        "construct", "destruct", "magic", "phpunit",
        "method_public", "method_protected", "method_private"
      ]
    },
    "ordered_imports": {
      "sort_algorithm": "alpha",
      "imports_order": ["class", "function", "const"]
    },
    "ordered_traits": true,
    "phpdoc_order": true,
    "phpdoc_separation": true,
    "phpdoc_types_order": { "null_adjustment": "always_last" },
    "return_type_declaration": { "space_before": "none" },
    "single_class_element_per_statement": true,
    "single_quote": true,
    "single_trait_insert_per_statement": true,
    "trailing_comma_in_multiline": {
      "elements": ["arrays", "arguments", "parameters"]
    },
    "visibility_required": { "elements": ["property", "method", "const"] },
    "void_return": true,
    "yoda_style": { "equal": true, "identical": true, "less_and_greater": false }
  }
}
```

## Rules Not Available in Pint

The following rules require PHPCS and cannot be replaced by Pint:

### Security (Critical)
- **Output Escaping**: All `echo`/`print` statements must use escape functions
- **Input Sanitization**: Superglobals must be sanitized before database operations

### Naming Conventions
- **Class Names**: PascalCase enforcement
- **Method/Function Names**: camelCase enforcement
- **Variable Names**: camelCase enforcement
- **Database Columns**: snake_case enforcement

### Other
- **Line Length**: 120 character limit
- **Disallowed Functions**: `die`, `exit`, `var_dump`, `print_r`, `create_function`
- **Blade-Specific Rules**: Colon syntax, no PHP tags

## Recommended Usage

For complete code style enforcement, use both packages together:

```bash
# Auto-fix with Pint first
./vendor/bin/pint

# Then check with PHPCS for remaining issues
./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
```

### CI/CD Pipeline

For CI/CD integration examples, see [CI/CD Integration Guide](Ci-Cd).

```yaml
jobs:
  code-style:
    steps:
      - name: Run Pint
        run: ./vendor/bin/pint --test

      - name: Run PHPCS
        run: ./vendor/bin/phpcs --standard=ArtisanPackUIStandard .
```

## Related Documentation

- [Home](Home)
- [Customization Guide](Customization)
- [CI/CD Integration](Ci-Cd)
