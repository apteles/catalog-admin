<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\FindById;

use Core\Application\UseCases\Category\Create\Output;
use Core\Domain\Repositories\CategoryRepository;

class FindCategoryByIdUseCase
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $categoryFromStorage = $this->categoryRepository->findById((string)$input->id);

        return new Output(
            id: $categoryFromStorage->id(),
            name: $categoryFromStorage->name(),
            description: $categoryFromStorage->description(),
            status: $categoryFromStorage->status(),
            created_at: $categoryFromStorage->createdAt()
        );
    }
}
