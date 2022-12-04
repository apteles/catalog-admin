<?php

declare(strict_types=1);

namespace Core\Domain\Validations;

class Str
{
    private function __construct(private string $value)
    {
    }

    public static function create(string $value): Str
    {
        return new self($value);
    }

    public function isEmpty(): bool
    {
        return empty(trim($this->value));
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function minLength(int $length): bool
    {
        return strlen($this->value) >= $length;
    }

    public function maxLength(int $length): bool
    {
        return strlen($this->value) <= $length;
    }
}
