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
        $categoryStub = new Category(
            $name,
            $description,
            $id,
        );

        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('findById')->andReturn($categoryStub);

        $inputStub = new Input(
            id:  $id
        );

        $listCategoryByIdUseCase = new FindCategoryByIdUseCase($categoryRepositoryMock);
        $output = $listCategoryByIdUseCase->execute($inputStub);

        $this->assertEquals($name, $output->name);
        $this->assertEquals($description, $output->description);
        $this->assertEquals($id, $output->id);
        $this->assertTrue(RamseyUuid::isValid((string)$output->id));

        $categoryRepositorySpy = m::spy(stdClass::class, CategoryRepository::class);
        $categoryRepositorySpy->shouldReceive('findById')->andReturn($categoryStub);
        $listCategoryByIdUseCase = new FindCategoryByIdUseCase($categoryRepositorySpy);
        $listCategoryByIdUseCase->execute($inputStub);
        $categoryRepositorySpy->shouldHaveReceived('findById');

        m::close();
    }
}

