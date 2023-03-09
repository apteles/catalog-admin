<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Genre;

use Core\Application\UseCases\Genre\Delete\Output;
use Core\Application\UseCases\Genre\Delete\DeleteGenreUseCase;
use Core\Application\UseCases\Genre\Delete\Input;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeleteGenreUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) Uuid::generate();

        // arrange
        $mockRepository = m::mock(stdClass::class, GenreRepository::class);

        // Expect
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(true);

        $mockInputDto = m::mock(Input::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);

        // action
        $response = $useCase->execute($mockInputDto);

        // assert
        $this->assertInstanceOf(Output::class, $response);
        $this->assertTrue($response->success);
    }

    public function test_delete_fail()
    {
        $uuid = (string) Uuid::generate();

        $mockRepository = m::mock(stdClass::class, GenreRepository::class);
        $mockRepository->shouldReceive('delete')
            ->times(1)
            ->with($uuid)
            ->andReturn(false);

        $mockInputDto = m::mock(Input::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }
}

