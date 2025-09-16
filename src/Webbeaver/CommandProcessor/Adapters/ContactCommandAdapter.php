<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\DTO\ContactCommandDTO;
use Psr\Log\LoggerInterface;

/**
 * Адаптер для команды "Contact".
 */
class ContactCommandAdapter implements CommandAdapterInterface
{
    /** @var int */
    private int $dealId;

    /**
     * @param int $dealId
     */
    public function __construct(int $dealId)
    {
        $this->dealId = $dealId;
    }

    /**
     * @param ContactCommandDTO $command
     * @param LoggerInterface $logger
     */
    public function handle(ContactCommandDTO $command, LoggerInterface $logger): void
    {
        $logger->info("Выполнение Contact для сделки {$this->dealId}: {$command->contactName}");
        // ...бизнес-логика...
    }
}

