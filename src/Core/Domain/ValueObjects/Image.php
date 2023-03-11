<?php

declare(strict_types=1);

namespace Core\Domain\ValueObjects;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class Image
{
    public function __construct(
        private readonly string $path,
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }
}
