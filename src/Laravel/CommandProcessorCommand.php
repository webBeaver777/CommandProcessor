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
        // Преобразуйте аргументы в CommandContext или нужный формат
        $context = new CommandContext($args);
        $processor = app(CommandProcessor::class);
        $result = $processor->process($context);
        $this->info('Результат: ' . print_r($result, true));
    }
}
