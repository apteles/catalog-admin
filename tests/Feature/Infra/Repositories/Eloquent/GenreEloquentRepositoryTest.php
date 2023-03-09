<?php

namespace Tests\Feature\Infra\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    use RefreshDatabase;
    protected GenreEloquentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new GenreModel());
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(GenreRepository::class, $this->repository);
    }

    public function testCreate()
    {
        $entity = new Genre(name: 'New genre');

        $genre = $this->repository->create($entity);
        $this->assertEquals($entity->name, $genre->name);
        $this->assertEquals($entity->id, $genre->id);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new Genre(name: 'New genre');
        $entity->deactivate();

        $this->repository->create($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(1)->create();
        $genre = new Genre(name: 'teste');
        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->create($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
        ]);

        $this->assertDatabaseCount('category_genre', 1);
    }

    public function testNotFoundById()
    {

        $this->expectException(NotFoundException::class);

        $genre = 'fake_value';

        $this->repository->findById($genre);
    }

    public function testFindById()
    {
        $genre = GenreModel::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id, $response->id());
        $this->assertEquals($genre->name, $response->name);
    }

    public function testFindAll()
    {
        $genres = GenreModel::factory()->count(10)->create();

        $genresDb = $this->repository->all();

        $this->assertEquals(count($genres), count($genresDb));
    }

    public function testFindAllEmpty()
    {

        $genresDb = $this->repository->all();

        $this->assertCount(0, $genresDb);
    }

    public function testFindAllWithFilter()
    {

        GenreModel::factory()->count(10)->state(new Sequence(
            fn (Sequence $sequence) => $sequence->index === 0 ? ['name' => 'Teste'] : [],
        ))->create();
        $genresDb = $this->repository->all(
            filter: 'Teste'
        );
        $this->assertEquals(1, count($genresDb));

        $genresDb = $this->repository->all();
        $this->assertEquals(10, count($genresDb));
    }

    public function testPagination()
    {
        GenreModel::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertEquals(15, count($response->items()));
        $this->assertEquals(60, $response->total());
    }

    public function testPaginationEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }

    public function testUpdate()
    {

        $genre = GenreModel::factory()->create();

        $entity = new Genre(
            name: $genre->name,
            id: Uuid::new($genre->id),
            isActive: (bool) $genre->is_active,
            createdAt: new DateTime($genre->created_at),
        );

        $entity->update(
            name: 'Name Updated'
        );

        $response = $this->repository->update($entity);

        $this->assertEquals('Name Updated', $response->name);

        $this->assertDatabaseHas('genres', [
            'name' => 'Name Updated',
        ]);
    }

    public function testUpdateNotFound()
    {

        $this->expectException(NotFoundException::class);
        $entity = new Genre(
            id: Uuid::generate(),
            name: 'name',
            isActive: true,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $entity->update(
            name: 'Name Updated'
        );

        $this->repository->update($entity);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function testDelete()
    {
        $genre = GenreModel::factory()->create();

        $response = $this->repository->delete($genre->id);

        $this->assertSoftDeleted('genres', [
            'id' => $genre->id,
        ]);
        $this->assertTrue($response);
    }
}
