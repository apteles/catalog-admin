<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Genre;
use Core\Application\UseCases\Genre\ListPaginated\Input;
use Core\Application\UseCases\Genre\ListPaginated\ListPaginatedGenreUseCase;
use Core\Application\UseCases\Genre\ListPaginated\Output;
use Core\Domain\Repositories\GenreRepository;
use Core\Shared\Domain\ListCollection;
use Core\Shared\Domain\PaginationInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListPaginatedGenresUseCaseTest extends TestCase
{
    public function test_usecase()
    {
        $mockRepository = m::mock(stdClass::class, GenreRepository::class);
        $mockRepository->shouldReceive('paginate')->once()->andReturn($this->mockPagination());

        $mockDtoInput = m::mock(Input::class, [
            'teste', 'desc', 1, 15,
        ]);

        $useCase = new ListPaginatedGenreUseCase($mockRepository);
        $response = $useCase->execute($mockDtoInput);

        $this->assertInstanceOf(Output::class, $response);

        m::close();

        /**
         * Spies
         */
        // arrange
        $spy = m::spy(stdClass::class, GenreRepository::class);
        $spy->shouldReceive('paginate')->andReturn($this->mockPagination());
        $sut = new ListPaginatedGenreUseCase($spy);

        // action
        $sut->execute($mockDtoInput);

        // assert
        $spy->shouldHaveReceived()->paginate(
            'teste', 'desc', 1, 15
        );
    }

    protected function mockPagination(array $items = [])
    {
        $categoryCollectionMock = $this->getMockFor(ListCollection::class, 'count', $items);
        $this->mockPagination = m::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($categoryCollectionMock);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }

    /**
     * @param class-string $aClassName
     * @param string $aMethod
     * @param mixed $aReturn
     * @return m\LegacyMockInterface|m\MockInterface
     */
    private function getMockFor(
        string $aClassName,
        string $aMethod,
        mixed $aReturn
    ): m\LegacyMockInterface|m\MockInterface {
        $categoryCollectionMock = m::mock($aClassName);
        $categoryCollectionMock->shouldReceive($aMethod)->andReturn($aReturn);
        return $categoryCollectionMock;
    }
}
