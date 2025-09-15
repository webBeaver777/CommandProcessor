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

    public function handle(string $args, CommandContext $context): void
    {
        $this->repository->setProperty($context->deal->id, 222, $args);
        $this->repository->addMessage($context->deal->id, "Причина закрытия установлена: {$args}");
    }
}
