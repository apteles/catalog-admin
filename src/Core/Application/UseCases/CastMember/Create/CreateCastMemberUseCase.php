<?php

namespace Core\Application\UseCases\CastMember\Create;

use Core\Domain\Entities\CastMember;
use Core\Domain\Entities\CastMemberType;
use Core\Domain\Repositories\CastMemberRepository;

class CreateCastMemberUseCase
{
    public function __construct(
        private readonly CastMemberRepository $repository
    ){

    }

    public function execute(Input $input): Output
    {
        $entity = new CastMember(
            name: $input->name,
            type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR
        );

        $this->repository->create($entity);

        return new Output(
            id: $entity->id(),
            name: $entity->name,
            type: $input->type,
            created_at: $entity->createdAt(),
        );
    }
}
