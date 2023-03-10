<?php

namespace Core\Application\UseCases\CastMember\ListPaginated;

use Core\Domain\Repositories\CastMemberRepository;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class ListPaginatedCastMembersUseCase
{

    public function __construct(
        private readonly CastMemberRepository $repository
    ){

    }

    public function execute(Input $input): Output
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPerPage,
        );

        return new Output(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
