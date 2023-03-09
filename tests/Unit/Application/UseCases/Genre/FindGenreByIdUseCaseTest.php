<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Genre;

use Core\Application\UseCases\Genre\Delete\Output;
use Core\Application\UseCases\Genre\Delete\DeleteGenreUseCase;
use Core\Application\UseCases\Genre\Delete\Input;
use Core\Application\UseCases\Genre\FindById\FindGenreByIdUseCase;
use Core\Domain\Entities\Genre;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\ValueObjects\Uuid;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class FindGenreByIdUseCaseTest extends TestCase
{
    public function test_list_single()
    {
        $uuid = (string) Uuid::generate();

        $mockEntity = m::mock(Genre::class, [
            'teste',Uuid::new($uuid), true, [],
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = m::mock(stdClass::class, GenreRepository::class);
        $mockRepository->shouldReceive('findById')->once()->with($uuid)->andReturn($mockEntity);

        $mockInputDto = m::mock(\Core\Application\UseCases\Genre\FindById\Input::class, [
            $uuid,
        ]);

        $useCase = new FindGenreByIdUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(\Core\Application\UseCases\Genre\FindById\Output::class, $response);

        m::close();
    }
}

