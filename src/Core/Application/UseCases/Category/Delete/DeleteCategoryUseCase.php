<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Category\Delete;

use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use Core\Application\UseCases\Category\Delete\Output;

class DeleteCategoryUseCase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ){
    }

    public function execute(Input $input): Output
    {
        try {
            $categoryFromStorage = $this->categoryRepository->findById(
                $input->id
            );
            $this->categoryRepository->delete( (string) $categoryFromStorage->id());

            return new Output(
                executed: true,
                message: ""
            );
        }catch (NotFoundException $ex) {
            return new Output(
                executed: false,
                message: $ex->getMessage(),
            );
        }
    }
}
