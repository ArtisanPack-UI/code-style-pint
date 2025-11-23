<?php

declare(strict_types=1);

namespace ArtisanPackUI\CodeStylePint\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishPintConfigCommand extends Command
{
    protected $signature = 'artisanpack:publish-pint-config
                            {--force : Overwrite existing configuration file}
                            {--wordpress : Use WordPress-style spacing (requires PHP-CS-Fixer)}';

    protected $description = 'Publish the ArtisanPack UI Pint configuration file';

    public function __construct(
        protected Filesystem $filesystem,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('wordpress')) {
            return $this->publishPhpCsFixerConfig();
        }

        return $this->publishPintConfig();
    }

    protected function publishPintConfig(): int
    {
        $targetPath = base_path('pint.json');
        $stubPath = dirname(__DIR__, 2).'/stubs/pint.json.stub';

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

    protected function publishPhpCsFixerConfig(): int
    {
        $targetPath = base_path('.php-cs-fixer.dist.php');
        $stubPath = dirname(__DIR__, 2).'/stubs/.php-cs-fixer.dist.php.stub';

        if ($this->filesystem->exists($targetPath) && ! $this->option('force')) {
            $this->error('.php-cs-fixer.dist.php already exists! Use --force to overwrite.');

            return self::FAILURE;
        }

        if (! $this->filesystem->exists($stubPath)) {
            $this->error('Could not find the .php-cs-fixer.dist.php stub file.');

            return self::FAILURE;
        }

        $this->filesystem->copy($stubPath, $targetPath);

        $this->info('ArtisanPack UI PHP-CS-Fixer configuration published successfully!');
        $this->newLine();
        $this->line('This configuration includes WordPress-style spacing:');
        $this->line('  - Spaces inside parentheses: <comment>if ( $var )</comment>');
        $this->line('  - Spaces inside brackets (variables): <comment>$array[ $key ]</comment>');
        $this->line('  - Spaces around concatenation: <comment>$a . $b</comment>');
        $this->newLine();
        $this->line('You can now run: <comment>./vendor/bin/php-cs-fixer fix</comment>');
        $this->newLine();
        $this->line('Note: You\'ll need to install PHP-CS-Fixer:');
        $this->line('  <comment>composer require --dev friendsofphp/php-cs-fixer</comment>');

        return self::SUCCESS;
    }
}
