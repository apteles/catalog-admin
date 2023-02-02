<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Delete;

class Input
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
