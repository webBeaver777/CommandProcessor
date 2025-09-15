<?php
namespace CommandProcessor\Core;

use CommandProcessor\Contracts\CommandHandlerInterface;
use CommandProcessor\DTO\CommandContext;

class CommandProcessor
{
    /** @var CommandHandlerInterface[] */
    private array $handlers = [];

    public function registerHandler(CommandHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function process(string $command, CommandContext $context): mixed
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($command, $context)) {
                return $handler->handle($command, $context);
            }
        }
        throw new \RuntimeException("No handler found for command: $command");
    }
}

