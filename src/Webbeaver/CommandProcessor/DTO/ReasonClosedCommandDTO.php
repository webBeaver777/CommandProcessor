<?php

namespace Webbeaver\CommandProcessor\DTO;

/**
 * DTO для команды "/причина_закрытия".
 */
class ReasonClosedCommandDTO extends CommandDTO
{
    public string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }
}
