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

    public function handle(string $command, CommandContext $context): mixed
    {
        $deal = $context->params['deal'] ?? null;
        if ($deal && ! empty($deal->contact)) {
            $message = 'Контакт клиента: '.$deal->contact;
        } else {
            $message = 'Контакт клиента не указан';
        }
        if ($deal && method_exists($context, 'repository') && $context->repository) {
            $context->repository->addMessage($deal->id, $message);
        } elseif ($deal && property_exists($context, 'repository') && $context->repository) {
            $context->repository->addMessage($deal->id, $message);
        }

        return $message;
    }

    public static function commandName(): string
    {
        return '/контакт';
    }
}
