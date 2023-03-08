<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Genre as GenreModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entities\Category;
use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Domain\Collection;
use Core\Shared\Domain\ListCollection;
use Core\Shared\Domain\PaginationInterface;
use DateTime;
use Exception;

/**
 * @template-implements GenreRepository<Genre>
 */
class GenreEloquentRepository implements GenreRepository
{
    public function __construct(private GenreModel $model)
    {
    }

    public function create(mixed $entity): Genre
    {
        $genre = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt(),
        ]);

        if (count($entity->categoriesId) > 0) {
            $genre->categories()->sync($entity->categoriesId);
        }

        return $this->toEntity($genre);
    }

    /**
     * @inheritDoc
     */
    public function all(string $filter = '', string $order = 'DESC'): Collection
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return new ListCollection($result->toArray());
    }

    /**
     * @inheritDoc
     */
    public function paginate(string $filter = '', string $order = 'DESC', int $currentPage = 1, int $total = 15,): PaginationInterface
    {
        $paginator = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->paginate($total);

        return new PaginationPresenter($paginator);
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function update(mixed $entity)
    {
        if(!$genreFromDatabase = $this->model->find($entity->id())) {
            throw new NotFoundException('Genre not found');
        }
       $genreFromDatabase->update([
         'name' => $entity->name,
       ]);

        if (count($entity->categoriesId) > 0) {
            $genreFromDatabase->categories()->sync($entity->categoriesId);
        }

       $genreFromDatabase->refresh();
       return $this->toEntity($genreFromDatabase);
    }


    /**
     * @param string $id
     * @return Genre
     * @throws NotFoundException
     */
    public function findById(string $id): Genre
    {
       if(!$genre = $this->model->find($id)) {
           throw new NotFoundException(
               sprintf("given %s does not exists", $id)
           );
       }
       return $this->toEntity($genre);
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function delete(string $id): bool
    {
        if (! $genreDb = $this->model->find($id)) {
            throw new NotFoundException('Category Not Found');
        }

        return $genreDb->delete();
    }

    /**
     * @param object $model
     * @return Genre
     * @throws Exception
     */
    public function toEntity(object $model): Genre
    {
        $id = $model->id ? Uuid::new($model->id) : Uuid::generate();
        $entity = new Genre(
            id: $id,
            name: $model->name,
            createdAt: $model->created_at,
        );
       $model->is_active ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
