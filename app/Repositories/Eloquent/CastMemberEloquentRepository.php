<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entities\CastMember;
use Core\Domain\Entities\CastMemberType;
use Core\Domain\Repositories\CastMemberRepository;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Domain\Collection;
use Core\Shared\Domain\ListCollection;
use Core\Shared\Domain\PaginationInterface;
use Core\Domain\Exceptions\NotFoundException;

class CastMemberEloquentRepository implements CastMemberRepository
{
    protected $model;

    public function __construct(CastMemberModel $model)
    {
        $this->model = $model;
    }

    public function create(mixed $entity): CastMember
    {
        $dataDb = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toEntity($dataDb);
    }

    public function findById(string $castMemberId): CastMember
    {
        if (! $dataDb = $this->model->find($castMemberId)) {
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");
        }

        return $this->toEntity($dataDb);
    }

    public function getIdsListIds(array $membersIds = []): array
    {
        return $this->model
                    ->whereIn('id', $membersIds)
                    ->pluck('id')
                    ->toArray();
    }

    public function all(string $filter = '', string $order = 'DESC'): Collection
    {
        $dataDb = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('name', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('name', $order)
                        ->get();

        return new ListCollection($dataDb->toArray());
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query = $query->orderBy('name', $order);
        $dataDb = $query->paginate($totalPage);

        return new PaginationPresenter($dataDb);
    }

    public function update(mixed $entity)
    {
        if (! $dataDb = $this->model->find($entity->id())) {
            throw new NotFoundException("Cast Member {$entity->id()} Not Found");
        }

        $dataDb->update([
            'name' => $entity->name,
            'type' => $entity->type->value,
        ]);

        $dataDb->refresh();

        return $this->toEntity($dataDb);
    }

    public function delete(string $id): bool
    {
        if (! $dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $dataDb->delete();
    }


    public function toEntity(object $model)
    {
        return new CastMember(
            id: Uuid::new($model->id),
            name: $model->name,
            type: CastMemberType::from($model->type),
            createdAt: $model->created_at
        );
    }
}
