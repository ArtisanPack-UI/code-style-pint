<?php

declare(strict_types=1);

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

describe('Pint Integration', function () {
    it('generates valid pint.json that Pint can parse', function () {
        $json = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->toJson();

        $decoded = json_decode($json, true);

        expect(json_last_error())->toBe(JSON_ERROR_NONE)
            ->and($decoded)->toBeArray()
            ->and($decoded)->toHaveKey('preset')
            ->and($decoded)->toHaveKey('rules')
            ->and($decoded)->toHaveKey('exclude');
    });

    it('can save config to a file', function () {
        $tempFile = sys_get_temp_dir().'/pint-test-'.uniqid().'.json';

        $result = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->save($tempFile);

        expect($result)->toBeTrue()
            ->and(file_exists($tempFile))->toBeTrue();

        $content = file_get_contents($tempFile);
        $decoded = json_decode($content, true);

        expect(json_last_error())->toBe(JSON_ERROR_NONE)
            ->and($decoded['preset'])->toBe('laravel');

        unlink($tempFile);
    });

    it('config has expected structure matching pint.json schema', function () {
        $config = PintConfigBuilder::create()
            ->withArtisanPackUIPreset()
            ->toArray();

        // Verify preset
        expect($config['preset'])->toBeString();

        // Verify rules structure
        expect($config['rules'])->toBeArray();
        foreach ($config['rules'] as $ruleName => $ruleConfig) {
            expect($ruleName)->toBeString();
            $isValidRuleConfig = is_bool($ruleConfig) || is_null($ruleConfig) || is_array($ruleConfig);
            expect($isValidRuleConfig)->toBeTrue("Rule '$ruleName' has invalid config type");
        }

        // Verify exclude structure
        expect($config['exclude'])->toBeArray();
        foreach ($config['exclude'] as $path) {
            expect($path)->toBeString();
        }
    });
});

describe('PublishPintConfigCommand', function () {
    it('command is registered', function () {
        $this->artisan('list')
            ->expectsOutputToContain('artisanpack:publish-pint-config');
    });
});

describe('Fixture Files', function () {
    it('before fixtures exist', function () {
        $fixturesPath = dirname(__DIR__).'/fixtures/before';

        expect(is_dir($fixturesPath))->toBeTrue();

        $files = glob($fixturesPath.'/*.php');

        expect($files)->not->toBeEmpty();
    });

    it('after fixtures exist', function () {
        $fixturesPath = dirname(__DIR__).'/fixtures/after';

        expect(is_dir($fixturesPath))->toBeTrue();

        $files = glob($fixturesPath.'/*.php');

        expect($files)->not->toBeEmpty();
    });

    it('before and after fixtures have matching files', function () {
        $beforePath = dirname(__DIR__).'/fixtures/before';
        $afterPath = dirname(__DIR__).'/fixtures/after';

        $beforeFiles = array_map('basename', glob($beforePath.'/*.php'));
        $afterFiles = array_map('basename', glob($afterPath.'/*.php'));

        sort($beforeFiles);
        sort($afterFiles);

        expect($beforeFiles)->toBe($afterFiles);
    });
});
