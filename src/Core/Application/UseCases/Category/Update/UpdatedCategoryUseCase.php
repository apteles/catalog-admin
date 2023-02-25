<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Update;

use Core\Domain\Repositories\CategoryRepository;

class UpdatedCategoryUseCase
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function execute(Input $input): Output
    {
        $categoryFromStorage = $this->categoryRepository->findById(
            $input->id
        );
        $categoryFromStorage->changeName($input->name);
        $categoryFromStorage->changeDescription( $input?->description ?? $categoryFromStorage->description());
        $categoryFromStorage = $this->categoryRepository->update($categoryFromStorage);

        return new Output(
            id: (string) $categoryFromStorage->id(),
            name: $categoryFromStorage->name(),
            description: $categoryFromStorage->description(),
            status: $categoryFromStorage->status(),
            created_at: $categoryFromStorage->createdAt()
        );
    }
}
