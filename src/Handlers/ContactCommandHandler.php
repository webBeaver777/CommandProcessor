<?php
namespace CommandProcessor\Handlers;

use CommandProcessor\Contracts\CommandHandlerInterface;
use CommandProcessor\DTO\CommandContext;

class ContactCommandHandler implements CommandHandlerInterface
{
    public function supports(string $command, CommandContext $context): bool
    {
        return str_starts_with(trim($command), '/contact');
    }

    public function handle(string $command, CommandContext $context): string
    {
        // Пример обработки команды
        return 'Контактная информация: info@example.com';
    }
}

