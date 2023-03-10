<?php

namespace Core\Application\UseCases\CastMember\FindById;

use Core\Domain\Repositories\CastMemberRepository;

class FindByIdCastMemberUseCase
{
    public function __construct(
        private readonly CastMemberRepository $repository
    ){
    }

    public function execute(Input $input): Output
    {
        $castMember = $this->repository->findById($input->id);

        return new Output(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            created_at: $castMember->createdAt(),
        );
    }
}
