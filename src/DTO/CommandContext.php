<?php
namespace CommandProcessor\DTO;

class CommandContext
{
    public function __construct(
        public readonly array $params = [],
        public readonly ?string $userId = null,
        public readonly array $meta = []
    ) {}
}

