<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\ListPaginated;

use Core\Domain\Repositories\CategoryCollection;

class Output
{
    public function __construct(
        public readonly CategoryCollection $items
    ) {
    }
}
