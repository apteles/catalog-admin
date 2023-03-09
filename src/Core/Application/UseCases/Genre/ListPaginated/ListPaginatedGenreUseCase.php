<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\ListPaginated;

use Core\Domain\Repositories\GenreRepository;

class ListPaginatedGenreUseCase
{
    public function __construct(
        private readonly GenreRepository $genreRepository
    ) {
    }

    public function execute(Input $input): Output
    {
        $genreFromStorage = $this->genreRepository->paginate(
            filter: $input->filter,
            order: $input->order,
            currentPage: $input->page,
            total: $input->totalPage
        );

      //  dd($genreFromStorage);
        return new Output(
            items: $genreFromStorage->items(),
            total: $genreFromStorage->total(),
            current_page: $genreFromStorage->currentPage(),
            last_page: $genreFromStorage->lastPage(),
            first_page: $genreFromStorage->firstPage(),
            per_page: $genreFromStorage->perPage(),
            to: $genreFromStorage->to(),
            from: $genreFromStorage->from(),
        );
    }
}
