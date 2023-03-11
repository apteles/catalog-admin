<?php

namespace Core\Application\UseCases\CastMember\ListPaginated;

class Input
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPerPage = 15,
    ) {
    }
}
