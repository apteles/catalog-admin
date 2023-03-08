<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Create;

class Input
{
    public function __construct(
        public string $name,
        public array $categoriesId = [],
        public bool $isActive = true,
    ) {
    }
}
