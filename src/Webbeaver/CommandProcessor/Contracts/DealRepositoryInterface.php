<?php

namespace Webbeaver\CommandProcessor\Contracts;

use Webbeaver\CommandProcessor\DTO\Deal;

interface DealRepositoryInterface
{
    public function getDeal(int $id): Deal;

    public function saveDeal(Deal $deal): void;

    public function setProperty(int $dealId, int $propertyId, mixed $value): void;

    public function getProperty(int $dealId, int $propertyId): mixed;

    public function addMessage(int $dealId, string $message): void;
}
