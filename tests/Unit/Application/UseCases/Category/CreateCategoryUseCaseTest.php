<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Category;

use Core\Application\UseCases\Category\Create\CreateCategoryUseCase;
use Core\Application\UseCases\Category\Create\Input;
use Core\Application\UseCases\Category\Create\Output;
use Core\Domain\Entities\Category;
use Core\Domain\Entities\CategoryStatus;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\ValueObjects\Uuid;
use DateTimeInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class CreateCategoryUseCaseTest extends TestCase
{
    public function testItShouldCreateANewCategory(): void
    {
        $name = 'category name';
        $description = 'some description';
        $id = (string) Uuid::generate();
        $categoryEntity = m::mock(Category::class, [
            $name,
            $description,
            $id
        ])->makePartial();
        $categoryEntity->shouldReceive('id')->andReturn($id);

        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('create')->andReturn($categoryEntity);
        $createCategoryUseCase = new CreateCategoryUseCase($categoryRepositoryMock);
        $inputMock = m::mock(Input::class, [
            'category name',
            'some description'
        ]);
        $output = $createCategoryUseCase->execute($inputMock);

        $this->assertInstanceOf(Output::class, $output);
        $this->assertEquals('some description', $output->description);
        $this->assertEquals('category name', $output->name);
        $this->assertEquals(CategoryStatus::ACTIVE, $output->status);
        $this->assertInstanceOf(DateTimeInterface::class, $output->created_at);
        $this->assertTrue(RamseyUuid::isValid((string)$output->id));

        $categoryRepositorySpy = m::spy(stdClass::class, CategoryRepository::class);
        $categoryRepositorySpy->shouldReceive('create')->andReturn($categoryEntity);
        $createCategoryUseCase = new CreateCategoryUseCase($categoryRepositorySpy);
        $createCategoryUseCase->execute($inputMock);
        $categoryRepositorySpy->shouldHaveReceived('create');
        m::close();
    }
}

