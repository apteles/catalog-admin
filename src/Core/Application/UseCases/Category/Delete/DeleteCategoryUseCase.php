<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Delete;

use Core\Domain\Repositories\CategoryRepository;

class DeleteCategoryUseCase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ){
    }

    public function execute(Input $input): Output
    {
        $categoryFromStorage = $this->categoryRepository->findById(
            $input->id
        );
        $this->categoryRepository->delete( (string) $categoryFromStorage->id());

        return new Output(
            executed: true,
            message: ""
        );
    }
}
