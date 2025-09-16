<?php

namespace Webbeaver\CommandProcessor\DTO;

/**
 * DTO для команды "/принято".
 */
class AcceptedCommandDTO extends CommandDTO
{
    public string $amount;

    public string $office;

    public function __construct(string $amount, string $office)
    {
        $this->amount = $amount;
        $this->office = $office;
    }
}
