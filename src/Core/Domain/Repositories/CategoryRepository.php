<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Category;

/**
 * @extends Repository<Category>
 */
interface CategoryRepository extends Repository
{
    public function getIdsListIds(array $categoriesId = []): array;
}
