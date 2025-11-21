<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Presets;

class ArtisanPackUIPreset
{
    /**
     * Get formatting rules.
     *
     * These rules control code formatting like indentation, spacing, and brace placement.
     */
    public function getFormattingRules(): array
    {
        return [
            'binary_operator_spaces' => [
                'default' => 'single_space',
                'operators' => [
                    '=' => 'align_single_space',
                    '=>' => 'align_single_space',
                ],
            ],
            'blank_line_after_opening_tag' => true,
            'braces_position' => [
                'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
                'control_structures_opening_brace' => 'same_line',
            ],
            'class_attributes_separation' => [
                'elements' => [
                    'method' => 'one',
                    'property' => 'one',
                    'trait_import' => 'none',
                ],
            ],
            'control_structure_braces' => true,
            'control_structure_continuation_position' => [
                'position' => 'same_line',
            ],
            'function_declaration' => [
                'closure_function_spacing' => 'one',
            ],
            'multiline_whitespace_before_semicolons' => [
                'strategy' => 'no_multi_line',
            ],
            'no_extra_blank_lines' => [
                'tokens' => [
                    'extra',
                    'throw',
                    'use',
                ],
            ],
            'return_type_declaration' => [
                'space_before' => 'none',
            ],
            'trailing_comma_in_multiline' => [
                'elements' => [
                    'arrays',
                    'arguments',
                    'parameters',
                ],
            ],
        ];
    }

    /**
     * Get code structure rules.
     *
     * These rules control how code is organized, including class structure and imports.
     */
    public function getCodeStructureRules(): array
    {
        return [
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'global_namespace_import' => [
                'import_classes' => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'no_unused_imports' => true,
            'ordered_class_elements' => [
                'order' => [
                    'use_trait',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public',
                    'property_protected',
                    'property_private',
                    'construct',
                    'destruct',
                    'magic',
                    'phpunit',
                    'method_public',
                    'method_protected',
                    'method_private',
                ],
            ],
            'ordered_imports' => [
                'sort_algorithm' => 'alpha',
                'imports_order' => [
                    'class',
                    'function',
                    'const',
                ],
            ],
            'ordered_traits' => true,
            'single_class_element_per_statement' => true,
            'single_trait_insert_per_statement' => true,
            'visibility_required' => [
                'elements' => [
                    'property',
                    'method',
                    'const',
                ],
            ],
        ];
    }

    /**
     * Get best practice rules.
     *
     * These rules enforce coding best practices like strict types, type declarations, and coding patterns.
     */
    public function getBestPracticeRules(): array
    {
        return [
            'declare_strict_types' => true,
            'fully_qualified_strict_types' => true,
            'phpdoc_order' => true,
            'phpdoc_separation' => true,
            'phpdoc_types_order' => [
                'null_adjustment' => 'always_last',
            ],
            'single_quote' => true,
            'void_return' => true,
            'yoda_style' => [
                'equal' => true,
                'identical' => true,
                'less_and_greater' => false,
            ],
        ];
    }

    /**
     * Get all rules combined.
     */
    public function getAllRules(): array
    {
        return array_merge(
            $this->getFormattingRules(),
            $this->getCodeStructureRules(),
            $this->getBestPracticeRules(),
        );
    }

    /**
     * Get default directories to exclude.
     */
    public function getDefaultExcludes(): array
    {
        return [
            'node_modules',
            'vendor',
            'bootstrap',
            'storage',
            'config',
            'database/migrations',
            'public',
            'resources/views',
        ];
    }

    /**
     * Get rule descriptions for documentation purposes.
     */
    public static function getRuleDescriptions(): array
    {
        return [
            'formatting' => [
                'binary_operator_spaces' => 'Controls spacing around binary operators. Aligns assignment operators for readability.',
                'blank_line_after_opening_tag' => 'Ensures a blank line after the opening PHP tag.',
                'braces_position' => 'Opening braces on same line for control structures, next line for functions.',
                'class_attributes_separation' => 'Controls blank lines between class elements (methods, properties, traits).',
                'control_structure_braces' => 'Ensures control structures use braces.',
                'control_structure_continuation_position' => 'Places else/elseif/catch on the same line as the closing brace.',
                'function_declaration' => 'Controls spacing in function declarations.',
                'multiline_whitespace_before_semicolons' => 'Prevents multiline whitespace before semicolons.',
                'no_extra_blank_lines' => 'Removes extra blank lines in specific contexts.',
                'return_type_declaration' => 'Controls spacing around return type declarations.',
                'trailing_comma_in_multiline' => 'Adds trailing commas in multiline arrays, arguments, and parameters.',
            ],
            'code_structure' => [
                'array_syntax' => 'Enforces short array syntax ([]).',
                'global_namespace_import' => 'Imports classes, functions, and constants from global namespace.',
                'no_unused_imports' => 'Removes unused import statements.',
                'ordered_class_elements' => 'Orders class elements: traits, constants, properties, constructor, methods.',
                'ordered_imports' => 'Alphabetically orders import statements by type.',
                'ordered_traits' => 'Alphabetically orders trait imports.',
                'single_class_element_per_statement' => 'One property/constant per statement.',
                'single_trait_insert_per_statement' => 'One trait per use statement.',
                'visibility_required' => 'Requires visibility on all properties, methods, and constants.',
            ],
            'best_practices' => [
                'declare_strict_types' => 'Adds declare(strict_types=1) to all files.',
                'fully_qualified_strict_types' => 'Converts FQCN in docblocks to short names when imported.',
                'phpdoc_order' => 'Orders PHPDoc tags consistently.',
                'phpdoc_separation' => 'Separates different groups of PHPDoc annotations.',
                'phpdoc_types_order' => 'Orders types in PHPDoc with null always last.',
                'single_quote' => 'Uses single quotes for simple strings.',
                'void_return' => 'Adds void return type where applicable.',
                'yoda_style' => 'Places literals on the left side of comparisons.',
            ],
        ];
    }
}
