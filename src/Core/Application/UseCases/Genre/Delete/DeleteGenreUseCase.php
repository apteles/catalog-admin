<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Delete;

use Core\Domain\Repositories\GenreRepository;

class DeleteGenreUseCase
{
    public function __construct(
        private readonly GenreRepository $repository
    ){
    }

    public function execute(Input $input): Output
    {
        $success = $this->repository->delete($input->id);

        return new Output(
            success: $success
        );
    }
}
