<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishPintConfigCommand extends Command
{
    protected $signature = 'artisanpack:publish-pint-config
                            {--force : Overwrite existing pint.json file}';

    protected $description = 'Publish the ArtisanPack UI Pint configuration file';

    public function __construct(
        protected Filesystem $filesystem,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $targetPath = base_path('pint.json');
        $stubPath   = dirname(__DIR__, 2) . '/stubs/pint.json.stub';

        if ($this->filesystem->exists($targetPath) && ! $this->option('force')) {
            $this->error('pint.json already exists! Use --force to overwrite.');

            return self::FAILURE;
        }

        if (! $this->filesystem->exists($stubPath)) {
            $this->error('Could not find the pint.json stub file.');

            return self::FAILURE;
        }

        $this->filesystem->copy($stubPath, $targetPath);

        $this->info('ArtisanPack UI Pint configuration published successfully!');
        $this->newLine();
        $this->line('You can now run: <comment>./vendor/bin/pint</comment>');

        return self::SUCCESS;
    }
}
