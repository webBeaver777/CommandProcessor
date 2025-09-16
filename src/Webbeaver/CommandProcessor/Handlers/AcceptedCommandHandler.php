<?php

namespace Webbeaver\CommandProcessor\Handlers;

use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class AcceptedCommandHandler implements CommandHandlerInterface
{
    public function __construct(private DealRepositoryInterface $repository) {}

    public static function commandName(): string
    {
        return '/принято';
    }

    public function handle(string $args, CommandContext $context): mixed
    {
        [$amount, $office] = explode(' ', $args, 2) + [null, null];

        $this->repository->setProperty($context->deal->id, 14, $amount);
        $this->repository->setProperty($context->deal->id, 15, $office);

        $this->repository->addMessage($context->deal->id, "Принято: сумма={$amount}, офис={$office}");

        return null;
    }

    public function supports(string $command, CommandContext $context): bool
    {
        return str_starts_with(trim($command), self::commandName());
    }
}
