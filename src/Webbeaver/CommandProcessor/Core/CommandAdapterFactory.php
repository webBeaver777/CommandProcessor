<?php

namespace Webbeaver\CommandProcessor\Core;

use Webbeaver\CommandProcessor\Contracts\CommandAdapterInterface;
use Webbeaver\CommandProcessor\DTO\CommandDTO;

/**
 * Фабрика адаптеров команд с регистрацией.
 */
class CommandAdapterFactory
{
    /**
     * @var array<class-string<CommandDTO>, callable>
     */
    private array $adapters = [];

    /**
     * Зарегистрировать адаптер для типа DTO.
     *
     * @param string $dtoClass
     * @param callable $factory (int $dealId) => CommandAdapterInterface
     */
    public function registerAdapter(string $dtoClass, callable $factory): void
    {
        $this->adapters[$dtoClass] = $factory;
    }

    /**
     * Получить адаптер по DTO.
     *
     * @param CommandDTO $dto
     * @param int $dealId
     * @return CommandAdapterInterface
     */
    public function getAdapter(CommandDTO $dto, int $dealId): CommandAdapterInterface
    {
        $dtoClass = get_class($dto);
        if (!isset($this->adapters[$dtoClass])) {
            throw new \RuntimeException("Адаптер для {$dtoClass} не зарегистрирован");
        }
        return ($this->adapters[$dtoClass])($dealId);
    }
}

