<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\ListPaginated;

use Core\Domain\Entities\Category;
use Core\Shared\Domain\Collection;

/**
 * @template T
 */
class Output
{
    /**
     * @param Collection<Category> $items
     */
    public function __construct(
        public readonly Collection $items,
        public readonly ?int $total,
        public readonly ?int $current_page,
        public readonly ?int $last_page,
        public readonly ?int $first_page,
        public readonly ?int $per_page,
        public readonly ?int $to,
        public readonly ?int $from,
    ) {
    }
}
