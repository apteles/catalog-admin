<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\ListPaginated;

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
        public int $total,
        public int $current_page,
        public int $last_page,
        public int $first_page,
        public int $per_page,
        public int $to,
        public int $from,
    ) {
    }
}
