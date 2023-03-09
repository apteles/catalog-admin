<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Genre;

use Core\Application\UseCases\Genre\Update\Input;
use Core\Application\UseCases\Genre\Update\Output;
use Core\Application\UseCases\Genre\Update\UpdateGenreUseCase;
use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Repository\Transaction;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class UpdateGenreUseCaseTest extends TestCase
{
    public function test_update()
    {
        $uuid = (string) Uuid::generate();

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockCategoryRepository($uuid),
            $this->mockTransaction(),
        );
        $response = $useCase->execute($this->mockUpdateInputDto($uuid, [$uuid]));

        $this->assertInstanceOf(Output::class, $response);
    }

    public function test_update_categories_notfound()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) Uuid::generate();

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid, 0),
            $this->mockCategoryRepository($uuid),
            $this->mockTransaction(),
        );
        $useCase->execute($this->mockUpdateInputDto($uuid, [$uuid, 'fake_value']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = m::mock(Genre::class, [
            'teste', Uuid::new($uuid), true, [],
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update')->times(1);
        $mockEntity->shouldReceive('addCategory');

        return $mockEntity;
    }

    private function mockRepository(string $uuid, int $timesCalled = 1)
    {
        $mockEntity = $this->mockEntity($uuid);

        $mockRepository = m::mock(stdClass::class, GenreRepository::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')
            ->times($timesCalled)
            ->andReturn($mockEntity);

        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = m::mock(stdClass::class, Transaction::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function mockCategoryRepository(string $uuid)
    {
        $mockCategoryRepository = m::mock(stdClass::class, CategoryRepository::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')
            ->once()
            ->andReturn([$uuid]);

        return $mockCategoryRepository;
    }

    private function mockUpdateInputDto(string $uuid, array $categoriesIds = [])
    {
        return m::mock(Input::class, [
            $uuid, 'name to update', $categoriesIds,
        ]);
    }

    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }
}

