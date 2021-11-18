<?php

declare(strict_types=1);

namespace App\Application\Dto;

class DiscountDto
{
    public function __construct(
        private readonly string $type,
        private string $value
    ){}

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}