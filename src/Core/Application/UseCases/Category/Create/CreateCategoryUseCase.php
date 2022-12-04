<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Create;

use Core\Domain\Entities\Category;
use Core\Domain\Repositories\CategoryRepository;

class CreateCategoryUseCase
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $category = Category::new(
            name: $input->name,
            description: $input->description
        );

        $categoryFromStorage = $this->categoryRepository->create($category);

        return new Output(
            id: $categoryFromStorage->id(),
            name: $categoryFromStorage->name(),
            description: $categoryFromStorage->description(),
            status: $categoryFromStorage->status(),
            created_at: $categoryFromStorage->createdAt()
        );
    }
}
