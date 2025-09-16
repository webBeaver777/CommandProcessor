<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Адаптер для команды "/причина_закрытия".
 */
class ReasonClosedCommandAdapter implements CommandAdapterInterface
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
        if (! $command instanceof \Webbeaver\CommandProcessor\DTO\ReasonClosedCommandDTO) {
            throw new \InvalidArgumentException('Неверный тип DTO для ReasonClosedCommandAdapter');
        }
        $deal = $this->dealRepository->getDeal($this->dealId);
        $deal->setProperty(222, $command->reason);
        $this->dealRepository->saveDeal($deal);
        $logger->info("Сделка {$this->dealId}: причина закрытия установлена — {$command->reason}");
    }
}
