<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Update;

class Output
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
    ) {
    }
}
