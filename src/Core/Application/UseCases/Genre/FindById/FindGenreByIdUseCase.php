<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\FindById;

use Core\Domain\Repositories\GenreRepository;

class FindGenreByIdUseCase
{
      public function __construct(
        private readonly GenreRepository $genreRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $genreFromStorage = $this->genreRepository->findById((string)$input->id);
        return new Output(
            id: (string) $genreFromStorage->id,
            name: $genreFromStorage->name,
            is_active: $genreFromStorage->isActive,
            created_at: $genreFromStorage->createdAt(),
        );
    }
}
