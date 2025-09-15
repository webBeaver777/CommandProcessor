<?php

namespace Webbeaver\CommandProcessor\Handlers;

use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class ContactCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DealRepositoryInterface $repository) {}

    public static function commandName(): string
    {
        return '/контакт';
    }

    public function handle(string $args, CommandContext $context): void
    {
        $contact = $context->deal->contact ?? 'неизвестно';
        $this->repository->addMessage($context->deal->id, "Контакт клиента: {$contact}");
    }
}
