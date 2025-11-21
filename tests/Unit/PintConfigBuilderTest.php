<?php

declare(strict_types=1);

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

describe('PintConfigBuilder', function () {
    it('creates an empty config with default preset', function () {
        $config = PintConfigBuilder::create()->toArray();

        expect($config)->toHaveKey('preset')
            ->and($config['preset'])->toBe('laravel');
    });

    it('can change the preset', function () {
        $config = PintConfigBuilder::create()
            ->preset('psr12')
            ->toArray();

        expect($config['preset'])->toBe('psr12');
    });

    it('can add a single rule', function () {
        $config = PintConfigBuilder::create()
            ->addRule('single_quote', true)
            ->toArray();

        expect($config['rules'])->toHaveKey('single_quote')
            ->and($config['rules']['single_quote'])->toBeTrue();
    });

    it('can add multiple rules', function () {
        $config = PintConfigBuilder::create()
            ->addRule('single_quote', true)
            ->addRule('array_syntax', ['syntax' => 'short'])
            ->toArray();

        expect($config['rules'])->toHaveKeys(['single_quote', 'array_syntax']);
    });

    it('can remove a rule', function () {
        $config = PintConfigBuilder::create()
            ->addRule('single_quote', true)
            ->addRule('array_syntax', ['syntax' => 'short'])
            ->removeRule('single_quote')
            ->toArray();

        expect($config['rules'])->not->toHaveKey('single_quote')
            ->and($config['rules'])->toHaveKey('array_syntax');
    });

    it('can set rules array directly', function () {
        $rules = [
            'single_quote' => true,
            'array_syntax' => ['syntax' => 'short'],
        ];

        $config = PintConfigBuilder::create()
            ->setRules($rules)
            ->toArray();

        expect($config['rules'])->toBe($rules);
    });

    it('can merge rules', function () {
        $config = PintConfigBuilder::create()
            ->addRule('single_quote', true)
            ->mergeRules([
                'array_syntax' => ['syntax' => 'short'],
                'single_quote' => false, // Override
            ])
            ->toArray();

        expect($config['rules']['single_quote'])->toBeFalse()
            ->and($config['rules']['array_syntax'])->toBe(['syntax' => 'short']);
    });

    it('can add exclusions as string', function () {
        $config = PintConfigBuilder::create()
            ->exclude('vendor')
            ->toArray();

        expect($config['exclude'])->toContain('vendor');
    });

    it('can add exclusions as array', function () {
        $config = PintConfigBuilder::create()
            ->exclude(['vendor', 'node_modules'])
            ->toArray();

        expect($config['exclude'])->toContain('vendor')
            ->and($config['exclude'])->toContain('node_modules');
    });

    it('can set exclusions directly', function () {
        $excludes = ['vendor', 'storage'];

        $config = PintConfigBuilder::create()
            ->setExclude($excludes)
            ->toArray();

        expect($config['exclude'])->toBe($excludes);
    });

    it('removes duplicate exclusions', function () {
        $config = PintConfigBuilder::create()
            ->exclude('vendor')
            ->exclude('vendor')
            ->exclude('storage')
            ->toArray();

        expect($config['exclude'])->toBe(['vendor', 'storage']);
    });

    it('outputs valid JSON', function () {
        $json = PintConfigBuilder::create()
            ->addRule('single_quote', true)
            ->exclude('vendor')
            ->toJson();

        $decoded = json_decode($json, true);

        expect(json_last_error())->toBe(JSON_ERROR_NONE)
            ->and($decoded)->toBeArray()
            ->and($decoded['rules']['single_quote'])->toBeTrue();
    });

    it('does not include empty rules array', function () {
        $config = PintConfigBuilder::create()->toArray();

        expect($config)->not->toHaveKey('rules');
    });

    it('does not include empty exclude array', function () {
        $config = PintConfigBuilder::create()->toArray();

        expect($config)->not->toHaveKey('exclude');
    });
});

describe('PintConfigBuilder with ArtisanPackUI Preset', function () {
    it('applies all rule groups by default', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->toArray();

        // Formatting rules
        expect($config['rules'])->toHaveKey('binary_operator_spaces')
            ->and($config['rules'])->toHaveKey('braces_position');

        // Code structure rules
        expect($config['rules'])->toHaveKey('array_syntax')
            ->and($config['rules'])->toHaveKey('ordered_imports');

        // Best practice rules
        expect($config['rules'])->toHaveKey('declare_strict_types')
            ->and($config['rules'])->toHaveKey('yoda_style');
    });

    it('can disable formatting rules', function () {
        $config = PintConfigBuilder::create()
            ->withFormattingRules(false)
            ->withArtisanPackUIPreset()
            ->toArray();

        expect($config['rules'])->not->toHaveKey('binary_operator_spaces')
            ->and($config['rules'])->toHaveKey('array_syntax')
            ->and($config['rules'])->toHaveKey('declare_strict_types');
    });

    it('can disable code structure rules', function () {
        $config = PintConfigBuilder::create()
            ->withCodeStructureRules(false)
            ->withArtisanPackUIPreset()
            ->toArray();

        expect($config['rules'])->toHaveKey('binary_operator_spaces')
            ->and($config['rules'])->not->toHaveKey('array_syntax')
            ->and($config['rules'])->toHaveKey('declare_strict_types');
    });

    it('can disable best practice rules', function () {
        $config = PintConfigBuilder::create()
            ->withBestPracticeRules(false)
            ->withArtisanPackUIPreset()
            ->toArray();

        expect($config['rules'])->toHaveKey('binary_operator_spaces')
            ->and($config['rules'])->toHaveKey('array_syntax')
            ->and($config['rules'])->not->toHaveKey('declare_strict_types');
    });

    it('includes default exclusions', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->toArray();

        expect($config['exclude'])->toContain('vendor')
            ->and($config['exclude'])->toContain('node_modules')
            ->and($config['exclude'])->toContain('storage');
    });

    it('can add custom exclusions to defaults', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->exclude('app/Legacy')
            ->toArray();

        expect($config['exclude'])->toContain('vendor')
            ->and($config['exclude'])->toContain('app/Legacy');
    });

    it('can override specific rules after applying preset', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->addRule('yoda_style', false)
            ->toArray();

        expect($config['rules']['yoda_style'])->toBeFalse();
    });

    it('can remove rules after applying preset', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->removeRule('declare_strict_types')
            ->toArray();

        expect($config['rules'])->not->toHaveKey('declare_strict_types');
    });
});
