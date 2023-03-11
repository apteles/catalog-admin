<?php

namespace Core\Application\UseCases\CastMember\Update;


use Core\Domain\Repositories\CastMemberRepository;

class UpdateCastMemberUseCase
{

    public function __construct(
        private readonly CastMemberRepository $repository
    ){
    }

    public function execute(Input $input): Output
    {
        $entity = $this->repository->findById($input->id);
        $entity->update(name: $input->name);

        $this->repository->update($entity);

        return new Output(
            id: $entity->id(),
            name: $entity->name,
            type: $entity->type->value,
            created_at: $entity->createdAt(),
        );
    }
}
