<?php

namespace Webbeaver\CommandProcessor\DTO;

final class Deal
{
    public function __construct(
        public int $id,
        public array $properties = [],
        public ?string $contact = null,
    ) {}

    public array $messages = [];

    public function setProperty(int $key, mixed $value): void
    {
        $this->properties[$key] = $value;
    }

    public function getProperty(int $key): mixed
    {
        return $this->properties[$key] ?? null;
    }

    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
