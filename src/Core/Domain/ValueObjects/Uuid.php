<?php

declare(strict_types=1);

namespace Core\Domain\ValueObjects;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    private function __construct(
        private string $value
    ) {
        $this->assertIsValid($this->value);
    }

    public static function new(string $value): Uuid
    {
        return new static($value);
    }

    public static function generate(): Uuid
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    private function assertIsValid(string $value): bool
    {
        return RamseyUuid::isValid($value) ?: throw new \InvalidArgumentException('Invalid UUID');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
