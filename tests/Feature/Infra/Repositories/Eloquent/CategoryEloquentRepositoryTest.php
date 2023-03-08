<?php

namespace Tests\Feature\Infra\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entities\Category;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use Core\Shared\Domain\PaginationInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{
    private CategoryEloquentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new CategoryModel());
    }

    use RefreshDatabase;


    public function testItShouldBeAbleCreateANewCategory(): void
    {
        $categoryEntity = $this->repository->create(Category::new('Category name', 'description...'));

        $this->assertInstanceOf(CategoryRepository::class, $this->repository);
        $this->assertInstanceOf(Category::class, $categoryEntity);

        $this->assertDatabaseHas(CategoryModel::class, [
            'name' => $categoryEntity->name(),
            'description' => $categoryEntity->description(),
        ]);

    }

    public function testItShouldBeAbleFindACategoryById(): void
    {
        $givenACategoryInDatabase = CategoryModel::factory()->create();

        $whenFindTheCategory = $this->repository->findById($givenACategoryInDatabase->id);

        $this->assertInstanceOf(Category::class, $whenFindTheCategory);
        $this->assertEquals($givenACategoryInDatabase->name, $whenFindTheCategory->name());
        $this->assertEquals($givenACategoryInDatabase->description, $whenFindTheCategory->description());
    }

    public function testItShouldThrowExceptionIfNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->repository->findById('invalid-id');
    }

    public function testItShouldBeAbleRetrieveACollectionOfCategories()
    {
        CategoryModel::factory()->count(10)->create();
        $collection = $this->repository->all();

        $this->assertCount(10, $collection);
    }

    public function testItShouldBeAblePaginateCategories()
    {
        CategoryModel::factory()->count(30)->create();
        $paginator = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $paginator);
        $this->assertCount(15, $paginator->items());
        $this->assertEquals(15, $paginator->perPage());
    }

    public function testItShouldThrowExceptionIfUpdatingAUnknownCategory(): void
    {
        $this->expectException(NotFoundException::class);
        $this->repository->update(Category::new('foo', 'bar'));
    }

    public function testItShouldBeAbleUpdateACategory(): void
    {
        $categoryFromDatabase = CategoryModel::factory()->create();
        $entityCategory = Category::newWithID(
            $categoryFromDatabase->id,
            $categoryFromDatabase->name,
            $categoryFromDatabase->description
        );
        $nameUpdated = "{$categoryFromDatabase->name} (updated)";
        $entityCategory->changeName($nameUpdated);
        $entityUpdated = $this->repository->update(
           $entityCategory
        );

        $this->assertEquals(
            $entityUpdated->name(),
            $nameUpdated
        );
    }
}
