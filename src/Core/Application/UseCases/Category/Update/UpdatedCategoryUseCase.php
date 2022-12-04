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
        $categoryFromStorage->changeDescription($input->description);
        $categoryFromStorage = $this->categoryRepository->update($categoryFromStorage);

        return new Output(
            $categoryFromStorage->name(),
            $categoryFromStorage->description()
        );
    }
}
