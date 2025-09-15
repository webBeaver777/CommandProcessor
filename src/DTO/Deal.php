<?php

namespace Webbeaver\CommandProcessor\DTO;

final class Deal
{
    public function __construct(
        public int $id,
        public array $properties = [],
        public ?string $contact = null,
    ) {}
}
