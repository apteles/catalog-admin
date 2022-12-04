<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Category;

use Core\Application\UseCases\Category\FindById\FindCategoryByIdUseCase;
use Core\Application\UseCases\Category\FindById\Input;
use Core\Domain\Entities\Category;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\ValueObjects\Uuid;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class FindCategoryByIdUseCaseTest extends TestCase
{
    public function testItShouldListACategoryById(): void
    {
        $name = 'category name';
        $description = 'some description';
        $id = (string) Uuid::generate();
        $categoryEntityMock = m::mock(Category::class, [
            $name,
            $description,
            $id
        ])->makePartial();
        $categoryEntityMock->shouldReceive('id')->andReturn($id);

        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('findById')->with($id)->andReturn($categoryEntityMock);

        $inputMock = m::mock(Input::class, [
           $id
        ]);

        $listCategoryByIdUseCase = new FindCategoryByIdUseCase($categoryRepositoryMock);
        $output = $listCategoryByIdUseCase->execute($inputMock);

        $this->assertEquals($name, $output->name);
        $this->assertEquals($description, $output->description);
        $this->assertEquals($id, $output->id);
        $this->assertTrue(RamseyUuid::isValid((string)$output->id));

        $categoryRepositorySpy = m::spy(stdClass::class, CategoryRepository::class);
        $categoryRepositorySpy->shouldReceive('findById')->with($id)->andReturn($categoryEntityMock);
        $listCategoryByIdUseCase = new FindCategoryByIdUseCase($categoryRepositorySpy);
        $listCategoryByIdUseCase->execute($inputMock);
        $categoryRepositorySpy->shouldHaveReceived('findById');

        m::close();
    }
}

