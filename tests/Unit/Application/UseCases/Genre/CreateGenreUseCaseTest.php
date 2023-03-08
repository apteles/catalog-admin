<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Genre;

use Core\Application\UseCases\Genre\Create\CreateGenreUseCase;
use Core\Application\UseCases\Genre\Create\Input;
use Core\Application\UseCases\Genre\Create\Output;
use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Repository\Transaction;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateGenreUseCaseTest extends TestCase
{
    public function test_create()
    {
        $uuid = (string) Uuid::generate();

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockCategoryRepository($uuid),
            $this->mockTransaction()
        );
        $response = $useCase->execute($this->mockCreateInputDto([$uuid]));

        $this->assertInstanceOf(Output::class, $response);
    }

    public function test_create_categories_notfound()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) Uuid::generate();

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid, 0),
            $this->mockCategoryRepository($uuid),
            $this->mockTransaction(),
        );
        $useCase->execute($this->mockCreateInputDto([$uuid, 'fake_value']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = m::mock(Genre::class, [
            'teste', Uuid::new($uuid), true, [],
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $uuid, int $timesCalled = 1)
    {
        $mockRepository = m::mock(stdClass::class, GenreRepository::class);
        $mockRepository->shouldReceive('create')
            ->times($timesCalled)
            ->andReturn($this->mockEntity($uuid));

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
        $mockCategoryRepository->shouldReceive('getIdsListIds')->once()->andReturn([$uuid]);

        return $mockCategoryRepository;
    }

    private function mockCreateInputDto(array $categoriesIds)
    {
        return m::mock(Input::class, [
            'name', $categoriesIds, true,
        ]);
    }

    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }
}

