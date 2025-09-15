<?php

namespace Webbeaver\CommandProcessor\Contracts;

use Webbeaver\CommandProcessor\DTO\CommandContext;

interface CommandHandlerInterface
{
    public static function commandName(): string;

    public function handle(string $args, CommandContext $context): void;
}
