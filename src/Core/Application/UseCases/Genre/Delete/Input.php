<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Delete;

class Input
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
