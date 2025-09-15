<?php

namespace Webbeaver\CommandProcessor\Laravel;

use Illuminate\Console\Command;
use Webbeaver\CommandProcessor\Core\CommandProcessor;
use Webbeaver\CommandProcessor\DTO\CommandContext;

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

        $deal = new \Webbeaver\CommandProcessor\DTO\Deal((int)$dealId);
        $context = new \Webbeaver\CommandProcessor\DTO\CommandContext($deal);

        $processor = app(\Webbeaver\CommandProcessor\Core\CommandProcessor::class);
        $result = $processor->process($commandText, $dealId);

        $this->info('Результат: ' . print_r($result, true));
        return 0;
    }
}
