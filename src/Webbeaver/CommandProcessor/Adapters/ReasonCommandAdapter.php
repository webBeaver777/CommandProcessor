<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Адаптер для команды "/причина".
 */
class ReasonCommandAdapter implements CommandAdapterInterface
{
    private int $dealId;

    private DealRepositoryInterface $dealRepository;

    public function __construct(int $dealId, DealRepositoryInterface $dealRepository)
    {
        $this->dealId = $dealId;
        $this->dealRepository = $dealRepository;
    }

    public function handle(CommandDTO $command, LoggerInterface $logger): void
    {
        if (! $command instanceof \Webbeaver\CommandProcessor\DTO\ReasonCommandDTO) {
            throw new \InvalidArgumentException('Неверный тип DTO для ReasonCommandAdapter');
        }
        $deal = $this->dealRepository->getDeal($this->dealId);
        $reason = $deal->getProperty(222);
        $logger->info("Сделка {$this->dealId}: причина закрытия — {$reason}");
        $deal->addMessage("Причина закрытия: {$reason}");
    }
}
