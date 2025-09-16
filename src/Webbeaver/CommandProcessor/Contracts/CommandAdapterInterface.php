<?php

namespace Webbeaver\CommandProcessor\Contracts;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Интерфейс адаптера команд.
 */
interface CommandAdapterInterface
{
    /**
     * Выполнить команду.
     */
    public function handle(CommandDTO $command, LoggerInterface $logger): void;
}
