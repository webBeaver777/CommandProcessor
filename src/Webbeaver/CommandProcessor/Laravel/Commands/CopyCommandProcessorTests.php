<?php

namespace Webbeaver\CommandProcessor\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyCommandProcessorTests extends Command
{
    protected $signature = 'command-processor:copy-tests';
    protected $description = 'Копирует тесты CommandProcessor в tests/Feature вашего проекта';

    public function handle()
    {
        $filesystem = new Filesystem();
        $source = __DIR__ . '/../../../../tests/Feature';
        $destination = base_path('tests/Feature');

        if (!$filesystem->exists($source)) {
            $this->error('Исходная папка с тестами не найдена: ' . $source);
            return 1;
        }

        $filesystem->ensureDirectoryExists($destination);
        $filesystem->copyDirectory($source, $destination);
        $this->info('Тесты успешно скопированы в ' . $destination);
        return 0;
    }
}

