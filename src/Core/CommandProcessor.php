<?php

namespace Webbeaver\CommandProcessor\Core;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class CommandProcessor
{
    private array $handlers = [];

    public function __construct(
        private DealRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {}

    public function registerHandler(string $command, CommandHandlerInterface $handler): void
    {
        $this->handlers[$command] = $handler;
    }

    public function process(string $input, int $dealId): void
    {
        [$command, $args] = $this->parse($input);

        if (!isset($this->handlers[$command])) {
            $this->logger->warning("Unknown command: {$command}");
            return;
        }

        $deal = $this->repository->getDeal($dealId);
        $context = new CommandContext($deal);

        $this->logger->info("Executing {$command} with args={$args}");

        $this->handlers[$command]->handle($args, $context);
    }

    private function parse(string $input): array
    {
        $parts = explode(' ', trim($input), 2);
        $command = $parts[0];
        $args = $parts[1] ?? '';
        return [$command, $args];
    }
}
