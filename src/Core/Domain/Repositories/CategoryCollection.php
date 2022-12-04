<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Category;
use Core\Shared\Domain\ListCollection;

/**
 * @extends ListCollection<Category>
 */
class CategoryCollection extends ListCollection
{
}
