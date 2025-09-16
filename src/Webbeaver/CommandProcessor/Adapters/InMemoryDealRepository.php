<?php

namespace Webbeaver\CommandProcessor\Adapters;

use Webbeaver\CommandProcessor\Contracts\DealRepositoryInterface;
use Webbeaver\CommandProcessor\DTO\Deal;

class InMemoryDealRepository implements DealRepositoryInterface
{
    private array $deals = [];

    public function getDeal(int $id): Deal
    {
        if (! isset($this->deals[$id])) {
            $this->deals[$id] = new Deal($id);
        }

        return $this->deals[$id];
    }

    public function saveDeal(Deal $deal): void
    {
        $this->deals[$deal->id] = $deal;
    }

    public function setProperty(int $dealId, int $propertyId, mixed $value): void
    {
        $this->deals[$dealId]->properties[$propertyId] = $value;
    }

    public function getProperty(int $dealId, int $propertyId): mixed
    {
        return $this->deals[$dealId]->properties[$propertyId] ?? null;
    }

    public function addMessage(int $dealId, string $message): void
    {
        // Сохраняем сообщения в массив, чтобы тесты могли их проверять
        if (! isset($this->deals[$dealId]->messages)) {
            $this->deals[$dealId]->messages = [];
        }
        $this->deals[$dealId]->messages[] = $message;
        echo "[Deal {$dealId}] {$message}\n";
    }

    public function getMessages(int $dealId): array
    {
        return $this->deals[$dealId]->messages ?? [];
    }
}
