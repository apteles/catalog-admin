<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entities\Category;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use Core\Shared\Domain\Collection;
use Core\Shared\Domain\ListCollection;
use Core\Shared\Domain\PaginationInterface;
use Exception;

/**
 * @template-implements CategoryRepository<Category>
 */
class CategoryEloquentRepository implements CategoryRepository
{
    public function __construct(private CategoryModel $model)
    {
    }

    public function create(mixed $entity): Category
    {
        $category = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name(),
            'description' => $entity->description(),
            'status' => $entity->status(),
            'created_at' => $entity->createdAt()
        ]);

        return $this->toEntity($category);
    }

    /**
     * @inheritDoc
     */
    public function all(string $filter = '', string $order = 'DESC'): Collection
    {
        $categories = $this->model->orderBy('id', $order)->get();
        return new ListCollection($categories->toArray());
    }

    /**
     * @inheritDoc
     */
    public function paginate(string $filter = '', string $order = 'DESC', int $currentPage = 1, int $total = 15,): PaginationInterface
    {
        if($filter) {
            $this->model->where('name', 'LIKE', "%{$filter}%");
        }
        $paginator = $this->model->orderBy('id', $order)->paginate();

        return new PaginationPresenter($paginator);
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function update(mixed $entity)
    {
        if(!$categoryFromDatabase = $this->model->find($entity->id())) {
            throw new NotFoundException('Category not found');
        }
       $categoryFromDatabase->update([
         'name' => $entity->name(),
         'description' => $entity->description(),
         'status' => $entity->status(),
       ]);
       $categoryFromDatabase->refresh();
       return $this->toEntity($categoryFromDatabase);
    }


    /**
     * @param string $id
     * @return Category
     * @throws NotFoundException
     */
    public function findById(string $id): Category
    {
       if(!$category = $this->model->find($id)) {
           throw new NotFoundException();
       }
       return $this->toEntity($category);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        return true;
    }

    /**
     * @param object $model
     * @return Category
     * @throws Exception
     */
    public function toEntity(object $model): Category
    {
        return Category::new(
            $model->name,
            $model->description
        );
    }
}
