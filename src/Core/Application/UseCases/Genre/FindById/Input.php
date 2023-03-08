<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\FindById;

use Core\Domain\ValueObjects\Uuid;

class Input
{
    public function __construct(
        public string|Uuid $id
    ) {
    }
}
