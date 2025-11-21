<?php

declare(strict_types=1);

use ArtisanPackUI\CodeStylePint\Presets\ArtisanPackUIPreset;

describe('ArtisanPackUIPreset', function () {
    beforeEach(function () {
        $this->preset = new ArtisanPackUIPreset();
    });

    describe('getFormattingRules', function () {
        it('returns an array of formatting rules', function () {
            $rules = $this->preset->getFormattingRules();

            expect($rules)->toBeArray()
                ->and($rules)->not->toBeEmpty();
        });

        it('includes binary operator spaces rule', function () {
            $rules = $this->preset->getFormattingRules();

            expect($rules)->toHaveKey('binary_operator_spaces')
                ->and($rules['binary_operator_spaces']['default'])->toBe('single_space');
        });

        it('includes braces position rule', function () {
            $rules = $this->preset->getFormattingRules();

            expect($rules)->toHaveKey('braces_position')
                ->and($rules['braces_position']['control_structures_opening_brace'])->toBe('same_line');
        });

        it('includes trailing comma rule', function () {
            $rules = $this->preset->getFormattingRules();

            expect($rules)->toHaveKey('trailing_comma_in_multiline')
                ->and($rules['trailing_comma_in_multiline']['elements'])->toContain('arrays');
        });
    });

    describe('getCodeStructureRules', function () {
        it('returns an array of code structure rules', function () {
            $rules = $this->preset->getCodeStructureRules();

            expect($rules)->toBeArray()
                ->and($rules)->not->toBeEmpty();
        });

        it('includes array syntax rule with short syntax', function () {
            $rules = $this->preset->getCodeStructureRules();

            expect($rules)->toHaveKey('array_syntax')
                ->and($rules['array_syntax']['syntax'])->toBe('short');
        });

        it('includes ordered imports rule', function () {
            $rules = $this->preset->getCodeStructureRules();

            expect($rules)->toHaveKey('ordered_imports')
                ->and($rules['ordered_imports']['sort_algorithm'])->toBe('alpha');
        });

        it('includes visibility required rule', function () {
            $rules = $this->preset->getCodeStructureRules();

            expect($rules)->toHaveKey('visibility_required')
                ->and($rules['visibility_required']['elements'])->toContain('property')
                ->and($rules['visibility_required']['elements'])->toContain('method')
                ->and($rules['visibility_required']['elements'])->toContain('const');
        });

        it('includes ordered class elements rule', function () {
            $rules = $this->preset->getCodeStructureRules();

            expect($rules)->toHaveKey('ordered_class_elements')
                ->and($rules['ordered_class_elements']['order'][0])->toBe('use_trait');
        });
    });

    describe('getBestPracticeRules', function () {
        it('returns an array of best practice rules', function () {
            $rules = $this->preset->getBestPracticeRules();

            expect($rules)->toBeArray()
                ->and($rules)->not->toBeEmpty();
        });

        it('includes declare strict types rule', function () {
            $rules = $this->preset->getBestPracticeRules();

            expect($rules)->toHaveKey('declare_strict_types')
                ->and($rules['declare_strict_types'])->toBeTrue();
        });

        it('includes single quote rule', function () {
            $rules = $this->preset->getBestPracticeRules();

            expect($rules)->toHaveKey('single_quote')
                ->and($rules['single_quote'])->toBeTrue();
        });

        it('includes yoda style rule', function () {
            $rules = $this->preset->getBestPracticeRules();

            expect($rules)->toHaveKey('yoda_style')
                ->and($rules['yoda_style']['equal'])->toBeTrue()
                ->and($rules['yoda_style']['identical'])->toBeTrue()
                ->and($rules['yoda_style']['less_and_greater'])->toBeFalse();
        });

        it('includes void return rule', function () {
            $rules = $this->preset->getBestPracticeRules();

            expect($rules)->toHaveKey('void_return')
                ->and($rules['void_return'])->toBeTrue();
        });
    });

    describe('getAllRules', function () {
        it('returns combined rules from all groups', function () {
            $allRules       = $this->preset->getAllRules();
            $formattingRules = $this->preset->getFormattingRules();
            $structureRules = $this->preset->getCodeStructureRules();
            $practiceRules  = $this->preset->getBestPracticeRules();

            $expectedCount = count($formattingRules) + count($structureRules) + count($practiceRules);

            expect(count($allRules))->toBe($expectedCount);
        });

        it('contains rules from formatting group', function () {
            $allRules = $this->preset->getAllRules();

            expect($allRules)->toHaveKey('binary_operator_spaces');
        });

        it('contains rules from code structure group', function () {
            $allRules = $this->preset->getAllRules();

            expect($allRules)->toHaveKey('array_syntax');
        });

        it('contains rules from best practices group', function () {
            $allRules = $this->preset->getAllRules();

            expect($allRules)->toHaveKey('declare_strict_types');
        });
    });

    describe('getDefaultExcludes', function () {
        it('returns an array of exclusions', function () {
            $excludes = $this->preset->getDefaultExcludes();

            expect($excludes)->toBeArray()
                ->and($excludes)->not->toBeEmpty();
        });

        it('includes common directories to exclude', function () {
            $excludes = $this->preset->getDefaultExcludes();

            expect($excludes)->toContain('vendor')
                ->and($excludes)->toContain('node_modules')
                ->and($excludes)->toContain('storage')
                ->and($excludes)->toContain('bootstrap');
        });

        it('excludes views directory', function () {
            $excludes = $this->preset->getDefaultExcludes();

            expect($excludes)->toContain('resources/views');
        });
    });

    describe('getRuleDescriptions', function () {
        it('returns descriptions for all rule groups', function () {
            $descriptions = ArtisanPackUIPreset::getRuleDescriptions();

            expect($descriptions)->toHaveKeys(['formatting', 'code_structure', 'best_practices']);
        });

        it('has descriptions for formatting rules', function () {
            $descriptions = ArtisanPackUIPreset::getRuleDescriptions();

            expect($descriptions['formatting'])->toHaveKey('binary_operator_spaces')
                ->and($descriptions['formatting']['binary_operator_spaces'])->toBeString();
        });

        it('has descriptions for code structure rules', function () {
            $descriptions = ArtisanPackUIPreset::getRuleDescriptions();

            expect($descriptions['code_structure'])->toHaveKey('array_syntax')
                ->and($descriptions['code_structure']['array_syntax'])->toBeString();
        });

        it('has descriptions for best practice rules', function () {
            $descriptions = ArtisanPackUIPreset::getRuleDescriptions();

            expect($descriptions['best_practices'])->toHaveKey('declare_strict_types')
                ->and($descriptions['best_practices']['declare_strict_types'])->toBeString();
        });
    });
});
