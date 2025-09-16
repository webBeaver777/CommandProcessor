<?php

namespace Webbeaver\CommandProcessor\Core;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandHandlerInterface;
use Webbeaver\CommandProcessor\DTO\CommandContext;

class CommandProcessor
{
    /** @var CommandHandlerInterface[] */
    private array $handlers = [];

    private LoggerInterface $logger;

    private $repository;

    public function __construct($repository, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->repository = $repository;
    }

    public function registerHandler(CommandHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function process(string $command, CommandContext $context): mixed
    {
        $this->logger->info("Processing command: {$command}", ['context' => $context]);
        foreach ($this->handlers as $handler) {
            if ($handler->supports($command, $context)) {
                $this->logger->info('Handler found: '.get_class($handler));

                return $handler->handle($command, $context);
            }
        }
        $this->logger->error("No handler found for command: {$command}");
        throw new \RuntimeException("No handler found for command: $command");
    }
}
