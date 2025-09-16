<?php
namespace Webbeaver\CommandProcessor\Handlers;

use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class ContactCommandHandler implements CommandHandlerInterface
{
    public function supports(string $command, CommandContext $context): bool
    {
        return str_starts_with(trim($command), self::commandName());
    }

    public function handle(string $command, CommandContext $context): string
    {
        $deal = $context->params['deal'] ?? null;
        if ($deal && !empty($deal->contact)) {
            return 'Контакт клиента: ' . $deal->contact;
        }
        return 'Контакт клиента не указан';
    }
    public static function commandName(): string
    {
        return '/контакт';
    }
}
