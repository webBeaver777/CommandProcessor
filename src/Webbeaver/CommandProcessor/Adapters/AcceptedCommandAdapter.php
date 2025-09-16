<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Psr\Log\LoggerInterface;
use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Адаптер для команды "/принято".
 */
class AcceptedCommandAdapter implements CommandAdapterInterface
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
        if (! $command instanceof \Webbeaver\CommandProcessor\DTO\AcceptedCommandDTO) {
            throw new \InvalidArgumentException('Неверный тип DTO для AcceptedCommandAdapter');
        }
        $deal = $this->dealRepository->getDeal($this->dealId);
        $deal->setProperty(14, $command->amount);
        $deal->setProperty(15, $command->office);
        $this->dealRepository->saveDeal($deal);
        $logger->info("Сделка {$this->dealId}: принято {$command->amount}, офис {$command->office}");
    }
}
