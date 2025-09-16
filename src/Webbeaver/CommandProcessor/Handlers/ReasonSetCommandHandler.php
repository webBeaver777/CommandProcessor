<?php

namespace Webbeaver\CommandProcessor\Handlers;

use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class ReasonSetCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DealRepositoryInterface $repository) {}

    public static function commandName(): string
    {
        return '/причина_закрытия';
    }

    public function handle(string $args, CommandContext $context): mixed
    {
        $deal = $context->params['deal'] ?? null;
        if (! $deal) {
            return null;
        }
        $argument = trim(str_replace(self::commandName(), '', $args));
        $this->repository->setProperty($deal->id, 222, $argument);
        $this->repository->addMessage($deal->id, "Причина закрытия установлена: {$argument}");

        return null;
    }

    public function supports(string $command, CommandContext $context): bool
    {
        return str_starts_with(trim($command), self::commandName());
    }
}
