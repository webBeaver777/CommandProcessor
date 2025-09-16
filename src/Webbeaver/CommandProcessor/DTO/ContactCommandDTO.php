<?php

namespace Webbeaver\CommandProcessor\DTO;

/**
 * DTO для команды "Contact".
 */
class ContactCommandDTO extends CommandDTO
{
    /** @var string */
    public string $contactName;

    /**
     * @param string $contactName
     */
    public function __construct(string $contactName)
    {
        $this->contactName = $contactName;
    }
}

