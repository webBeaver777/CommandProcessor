<?php

namespace Webbeaver\CommandProcessor\Contracts;

use Webbeaver\CommandProcessor\DTO\CommandDTO;
use Psr\Log\LoggerInterface;

/**
 * Интерфейс адаптера команд.
 */
interface CommandAdapterInterface
{
    /**
     * Выполнить команду.
     *
     * @param CommandDTO $command
     * @param LoggerInterface $logger
     * @return void
     */
    public function handle(CommandDTO $command, LoggerInterface $logger): void;
}

