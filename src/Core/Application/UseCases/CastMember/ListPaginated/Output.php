<?php

namespace Core\Application\UseCases\CastMember\ListPaginated;

use Core\Shared\Domain\Collection;

class Output
{
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
