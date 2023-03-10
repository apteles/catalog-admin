<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Category;

use Core\Application\UseCases\Category\Delete\Input;
use Core\Application\UseCases\Category\Delete\Output;
use Core\Application\UseCases\Category\Delete\DeleteCategoryUseCase;
use Core\Domain\Entities\Category;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CategoryRepository;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use stdClass;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function testItShouldBeAbleDeleteCategory(): void
    {
        $aCategory = Category::new(
            name: 'some name',
            description: 'some description'
        );
        $categoryRepositoryMock = $this->giveARepositoryWith($aCategory);
        $output = $this->whenExecutedWith(
            $categoryRepositoryMock,
            (string) $aCategory->id()
        );
        $this->thenAssertThatWasExcluded($output);
        $categoryRepositoryMock = $this->andGiveARepositoryThatNotFound($aCategory);
        $this->whenExecutedWithThenAssertThatWasNoDeleted(
            $categoryRepositoryMock,
            (string) $aCategory->id()
        );
    }

    private function giveARepositoryWith(Category $category): CategoryRepository
    {

        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('findById')->with((string)$category->id())->andReturn($category);
        $categoryRepositoryMock->shouldReceive('delete');

        return $categoryRepositoryMock;
    }

    /**
     * @param CategoryRepository $categoryRepositoryMock
     * @param Category $aCategory
     * @return Output
     */
    private function whenExecutedWith(CategoryRepository $categoryRepositoryMock, string $id): Output
    {
        $this->expectException(NotFoundException::class);

        $useCase = new DeleteCategoryUseCase($categoryRepositoryMock);
        return $useCase->execute(new Input(
            id: $id,
        ));
    }

    /**
     * @param $output
     * @return void
     */
    private function thenAssertThatWasExcluded(Output $output): void
    {
        $this->assertTrue(
            $output->executed,
        );
    }

    private function andGiveARepositoryThatNotFound(Category $category): CategoryRepository
    {
        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('findById')->andThrow(NotFoundException::class);

        return $categoryRepositoryMock;
    }

    /**
     * @param CategoryRepository $categoryRepositoryMock
     * @param Category $aCategory
     * @return Output
     */
    private function whenExecutedWithThenAssertThatWasNoDeleted(CategoryRepository $categoryRepositoryMock, string $id): Output
    {
        $this->expectException(NotFoundException::class);

        $useCase = new DeleteCategoryUseCase($categoryRepositoryMock);
        return $useCase->execute(new Input(
            id: $id,
        ));
    }
}

