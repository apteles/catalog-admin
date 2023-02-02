<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Category;

use Core\Application\UseCases\Category\Update\Input;
use Core\Application\UseCases\Category\Update\UpdatedCategoryUseCase;
use Core\Domain\Entities\Category;
use Core\Domain\Repositories\CategoryRepository;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use stdClass;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function testItShouldBeAbleUpdateCategory(): void
    {
        $category = Category::new(
            name: 'some name',
            description: 'some description'
        );

        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('findById')->with((string) $category->id())->andReturn($category);
        $category->changeName('name (updated)');
        $category->changeDescription('description (updated)');
        $categoryRepositoryMock->shouldReceive('update')->with($category)->andReturn($category);

        $useCase = new UpdatedCategoryUseCase($categoryRepositoryMock);
        $inputMock = m::mock(Input::class, [
            $category->id(),
            'some name (updated)',
            'some description (updated)'
        ]);
        $output = $useCase->execute($inputMock);

        $this->assertEquals(
            'some name (updated)',
            $output->name,
        );
        $this->assertEquals('some description (updated)', $output->description);
    }

    public function testItShouldNotUpdatedCategoryIfDoesNotExists(): void
    {
        $this->markTestSkipped('TODO: Implement');
    }
}

