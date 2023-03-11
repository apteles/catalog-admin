<?php

declare(strict_types=1);

namespace Core\Domain\ValueObjects;

use Core\Domain\Entities\MediaStatus;

final class Media
{
    public function __construct(
        private readonly string $filePath,
        private readonly MediaStatus $mediaStatus,
        private readonly string $encodedPath = '',
    ) {
    }

    public function __get($property)
    {
        return $this->{$property};
    }
}
