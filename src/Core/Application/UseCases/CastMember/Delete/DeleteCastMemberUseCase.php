<?php

namespace Core\Application\UseCases\CastMember\Delete;


use Core\Domain\Repositories\CastMemberRepository;

class DeleteCastMemberUseCase
{

    public function __construct(
        private readonly CastMemberRepository $repository
    ){
    }

    public function execute(Input $input): Output
    {
        $hasDeleted = $this->repository->delete($input->id);

        return new Output(
            success: $hasDeleted
        );
    }
}
