<?php
namespace CommandProcessor\Contracts;

use CommandProcessor\DTO\CommandContext;

interface CommandHandlerInterface
{
    /**
     * Проверяет, может ли обработчик обработать команду.
     */
    public function supports(string $command, CommandContext $context): bool;

    /**
     * Обрабатывает команду.
     */
    public function handle(string $command, CommandContext $context): mixed;
}

