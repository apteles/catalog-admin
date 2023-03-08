<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Update;

class Input
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $categoriesId = [],
    ) {
    }
}
