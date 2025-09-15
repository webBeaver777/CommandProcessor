<?php

namespace Webbeaver\CommandProcessor\DTO;

final class CommandContext
{
    public function __construct(
        public Deal $deal
    ) {}
}
