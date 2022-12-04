<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Create;

use Core\Domain\Entities\CategoryStatus;

class Input
{
    public function __construct(
        public string $name,
        public string $description = '',
        public CategoryStatus $status = CategoryStatus::ACTIVE
    ) {
    }
}
