<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Адаптер для команды "Contact".
 */
class ContactCommandAdapter implements CommandAdapterInterface
{
    private int $dealId;

    private \Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface $dealRepository;

    public function __construct(int $dealId, \Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface $dealRepository)
    {
        $this->dealId = $dealId;
        $this->dealRepository = $dealRepository;
    }

    public function handle(CommandDTO $command, LoggerInterface $logger): void
    {
        if (! $command instanceof \Webbeaver\CommandProcessor\DTO\ContactCommandDTO) {
            throw new \InvalidArgumentException('Неверный тип DTO для ContactCommandAdapter');
        }
        $deal = $this->dealRepository->getDeal($this->dealId);
        $contact = $command->contactName;
        $logger->info("Выполнение Contact для сделки {$this->dealId}: {$contact}");
        $deal->addMessage("Контакт клиента: {$contact}");
    }
}
