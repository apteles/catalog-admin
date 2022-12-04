<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\ListPaginated;

use Core\Domain\Repositories\CategoryCollection;
use Core\Domain\Repositories\CategoryRepository;

class ListPaginatedCategoriesUseCase
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $categoriesFromStorage = $this->categoryRepository->paginate(
            $input->filter
        );
        /** @var CategoryCollection $items */
        $items = $categoriesFromStorage->items();
        return new Output(
            items: $items
        );
    }
}
