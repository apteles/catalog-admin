<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\ListPaginated;

use Core\Domain\Repositories\CategoryCollection;
use Core\Domain\Repositories\CategoryRepository;
use Core\Shared\Domain\Collection;
use Core\Shared\Domain\ListCollection;

class ListPaginatedCategoriesUseCase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $categoriesFromStorage = $this->categoryRepository->paginate(
            filter: $input->filter,
            order: $input->order,
            currentPage: $input->page,
            total: $input->totalPage
        );
        return new Output(
            items: $categoriesFromStorage->items(),
            total: $categoriesFromStorage->total(),
//            current_page: $categoriesFromStorage->currentPage(),
//            last_page: $categoriesFromStorage->lastPage(),
//            first_page: $categoriesFromStorage->firstPage(),
//            per_page: $categoriesFromStorage->perPage(),
//            to: $categoriesFromStorage->to(),
//            from: $categoriesFromStorage->from(),
        );
    }
}
