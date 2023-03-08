<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Create;

use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\Repositories\CategoryRepository;
use Core\Shared\Repository\Transaction;

class CreateGenreUseCase
{
    /**
     * @param GenreRepository $repository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly CategoryRepository $categoryRepository,
        private readonly Transaction $transaction,
    ) {
    }

    /**
     * @throws \Throwable
     * @throws NotFoundException
     */
    public function execute(Input $input): Output
    {
        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId
            );

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->create($genre);

            $this->transaction->commit();

            return new Output(
                id: (string) $genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    /**
     * @throws NotFoundException
     */
    private function validateCategoriesId(array $categoriesId = []): void
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}
