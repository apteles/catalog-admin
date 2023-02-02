<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Delete;

class Output
{
    public function __construct(
        public readonly bool $executed,
        public readonly string $message,
    ) {
    }
}
