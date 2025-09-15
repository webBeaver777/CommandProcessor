<?php

namespace Webbeaver\CommandProcessor\Handlers;

use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class ReasonShowCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DealRepositoryInterface $repository) {}

    public static function commandName(): string
    {
        return '/причина';
    }

    public function handle(string $args, CommandContext $context): void
    {
        $reason = $this->repository->getProperty($context->deal->id, 222) ?? 'не указана';
        $this->repository->addMessage($context->deal->id, "Причина закрытия: {$reason}");
    }
}
