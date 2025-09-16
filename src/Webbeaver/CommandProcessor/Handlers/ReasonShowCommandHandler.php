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

    public function handle(string $args, CommandContext $context): mixed
    {
        $deal = $context->params['deal'] ?? null;
        if (! $deal) {
            return null;
        }
        $reason = $this->repository->getProperty($deal->id, 222) ?? 'не указана';
        $this->repository->addMessage($deal->id, "Причина закрытия: {$reason}");

        return null;
    }

    public function supports(string $command, CommandContext $context): bool
    {
        return str_starts_with(trim($command), self::commandName());
    }
}
