<?php

namespace Webbeaver\CommandProcessor\Laravel;

use Illuminate\Console\Command;

class CommandProcessorCommand extends Command
{
    protected $signature = 'command:processor {arguments* : Аргументы для процессора команд}';

    protected $description = 'Выполнить обработку команд через CommandProcessor';

    public function handle()
    {
        $args = $this->argument('arguments');
        if (count($args) < 2) {
            $this->error('Нужно передать как минимум ID сделки и команду. Пример: php artisan command:processor 42 "/принято 500 офис"');

            return 1;
        }
        $dealId = array_shift($args);
        $commandText = implode(' ', $args);

        $processor = app(\Webbeaver\CommandProcessor\Core\CommandProcessor::class);
        $context = new \Webbeaver\CommandProcessor\DTO\CommandContext(['dealId' => $dealId]);
        $result = $processor->process($commandText, $context);

        $this->info('Результат: '.print_r($result, true));

        return 0;
    }
}
