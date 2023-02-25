<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Update;

use Core\Domain\Entities\CategoryStatus;
use DateTimeInterface;

class Output
{
    public function __construct(
        public string|Uuid $id,
        public string $name,
        public string $description,
        public CategoryStatus $status,
        public DateTimeInterface $created_at,
    ) {
    }
}
