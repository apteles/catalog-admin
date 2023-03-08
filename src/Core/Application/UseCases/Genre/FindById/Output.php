<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\FindById;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active = true,
        public string $created_at = '',
    ) {
    }
}
