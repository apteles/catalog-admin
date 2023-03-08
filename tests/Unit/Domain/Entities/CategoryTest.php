<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use Core\Domain\Entities\Category;
use Core\Domain\Entities\CategoryStatus;
use Core\Domain\Exceptions\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryTest extends TestCase
{

    public function testItShouldBeAbleValidNameWhenCreatingACategory(): void
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Empty name is not allowed.');

        Category::new(
            name: '',
            description: 'Any description'
        );
    }

    public function testItShouldBeAbleValidDescriptionWhenCreatingACategory(): void
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Name must have at least more than 2 characters.');
        Category::new(
            name: 'C',
            description: 'An'
        );
    }

    public function testItShouldCreateANewCategory(): void
    {
        $category = Category::new(
            name: 'Cat 01',
            description: 'Any description'
        );

        $this->assertEquals('Cat 01', $category->name()); // @phpstan-ignore-line
        $this->assertEquals('Any description', $category->description()); // @phpstan-ignore-line
        $this->assertEquals(CategoryStatus::ACTIVE, $category->status()); // @phpstan-ignore-line
    }

    public function testItShouldChangeStatusOfCategory(): void
    {
        $category = Category::new(
            name: 'Cat 01',
            description: 'Any description'
        );
        $category->deactivate();
        $this->assertEquals(CategoryStatus::INACTIVE, $category->status());
        $category->activate();
        $this->assertEquals(CategoryStatus::ACTIVE, $category->status());
    }

    public function testItShouldBeAbleUpdateCategory(): void
    {
        $category = Category::new(
            name: 'Cat 01',
            description: 'Any description'
        );

        $category->update(
            name: 'name_updated',
            description: 'description_updated',
        );
        $this->assertEquals('name_updated', $category->name()); // @phpstan-ignore-line
        $this->assertEquals('description_updated', $category->description()); // @phpstan-ignore-line
    }
}
