<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Config;

use ArtisanPackUI\CodeStylePint\Presets\ArtisanPackUIPreset;

class PintConfigBuilder
{
    protected string $preset = 'laravel';

    protected array $rules = [];

    protected array $exclude = [];

    protected bool $formattingRules = true;

    protected bool $codeStructureRules = true;

    protected bool $bestPracticeRules = true;

    public static function create(): self
    {
        return new self();
    }

    public function preset(string $preset): self
    {
        $this->preset = $preset;

        return $this;
    }

    public function withFormattingRules(bool $enabled = true): self
    {
        $this->formattingRules = $enabled;

        return $this;
    }

    public function withCodeStructureRules(bool $enabled = true): self
    {
        $this->codeStructureRules = $enabled;

        return $this;
    }

    public function withBestPracticeRules(bool $enabled = true): self
    {
        $this->bestPracticeRules = $enabled;

        return $this;
    }

    public function withArtisanPackUIPreset(): self
    {
        $preset = new ArtisanPackUIPreset();

        if ($this->formattingRules) {
            $this->rules = array_merge($this->rules, $preset->getFormattingRules());
        }

        if ($this->codeStructureRules) {
            $this->rules = array_merge($this->rules, $preset->getCodeStructureRules());
        }

        if ($this->bestPracticeRules) {
            $this->rules = array_merge($this->rules, $preset->getBestPracticeRules());
        }

        $this->exclude = array_merge($this->exclude, $preset->getDefaultExcludes());

        return $this;
    }

    public function addRule(string $name, mixed $config): self
    {
        $this->rules[$name] = $config;

        return $this;
    }

    public function removeRule(string $name): self
    {
        unset($this->rules[$name]);

        return $this;
    }

    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function mergeRules(array $rules): self
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    public function exclude(string|array $paths): self
    {
        $paths = is_array($paths) ? $paths : [$paths];
        $this->exclude = array_merge($this->exclude, $paths);

        return $this;
    }

    public function setExclude(array $paths): self
    {
        $this->exclude = $paths;

        return $this;
    }

    public function toArray(): array
    {
        $config = [
            'preset' => $this->preset,
        ];

        if (! empty($this->rules)) {
            $config['rules'] = $this->rules;
        }

        if (! empty($this->exclude)) {
            $config['exclude'] = array_values(array_unique($this->exclude));
        }

        return $config;
    }

    public function toJson(int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES): string
    {
        return json_encode($this->toArray(), $flags);
    }

    public function save(string $path): bool
    {
        return file_put_contents($path, $this->toJson()) !== false;
    }
}
