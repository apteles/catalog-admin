<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\ListPaginated;

class Input
{
    public function __construct(
        public readonly string $filter = '',
        public readonly string $order = 'DESC',
        public readonly int $page = 1,
        public readonly int $totalPage = 15,
    ) {
    }
}
