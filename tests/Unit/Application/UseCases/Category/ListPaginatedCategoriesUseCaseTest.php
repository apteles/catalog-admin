<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Category;

use Core\Application\UseCases\Category\ListPaginated\Input;
use Core\Application\UseCases\Category\ListPaginated\ListPaginatedCategoriesUseCase;
use Core\Application\UseCases\Category\ListPaginated\Output;
use Core\Domain\Repositories\CategoryCollection;
use Core\Domain\Repositories\CategoryRepository;
use Core\Shared\Domain\PaginationInterface;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use stdClass;
use Countable;

class ListPaginatedCategoriesUseCaseTest extends TestCase
{
    public function testItShouldListCategoriesPaginatedWithZeroItems(): void
    {
        $this->markTestSkipped();
        /** @var CategoryRepository $repositoryWithZeroItems */
        $repositoryWithZeroItems = $this->givenARepositoryWith(
            items: 0
        );
        $output = $this->whenExecutedWith(
            aRepository: $repositoryWithZeroItems
        );
        $this->thenAssertWithZeroItems($output->items);
    }

    public function testItShouldListCategoriesPaginatedWithMoreThanZeroItems(): void
    {
        $this->markTestSkipped();
        /** @var CategoryRepository $repositoryWithTwoItems */
        $repositoryWithTwoItems = $this->givenARepositoryWith(
            items: 2
        );
        $output = $this->whenExecutedWith(
            aRepository: $repositoryWithTwoItems
        );
        $this->thenAssertInstanceOfCategoryCollectionWithTwoItems($output->items);
    }

    private function givenARepositoryWith(int $items): m\LegacyMockInterface|m\MockInterface|CategoryRepository
    {
        $categoryCollectionMock = $this->getMockFor(CategoryCollection::class, 'count', $items);
        $paginateMock = m::mock(stdClass::class, PaginationInterface::class);
        $paginateMock->shouldReceive('items')->andReturn($categoryCollectionMock);

        $repositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $repositoryMock->shouldReceive('paginate')->andReturn($paginateMock);
        return $repositoryMock;
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

    private function whenExecutedWith(CategoryRepository $aRepository): Output
    {
        $useCase = new ListPaginatedCategoriesUseCase($aRepository);
        $inputMock = m::mock(Input::class, ['']);
        return $useCase->execute($inputMock);
    }

    private function thenAssertWithZeroItems(Countable $items): void
    {
        $this->assertCount(0, $items);
    }

    private function thenAssertInstanceOfCategoryCollectionWithTwoItems(Countable $items): void
    {
        $this->assertCount(2, $items);
        $this->assertInstanceOf(CategoryCollection::class, $items);
    }
}
