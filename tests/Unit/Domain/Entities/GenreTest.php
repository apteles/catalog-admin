<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use Core\Domain\Entities\Genre;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\ValueObjects\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{

    public function testAttributes()
    {
        $uuid =  (string) \Core\Domain\ValueObjects\Uuid::generate();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: Uuid::new($uuid),
            name: 'New Genre',
            isActive: false,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(false, $genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreate()
    {
        $genre = new Genre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testDeactivate()
    {
        $genre = new Genre(
            name: 'teste'
        );

        $this->assertTrue($genre->isActive);

        $genre->deactivate();

        $this->assertFalse($genre->isActive);
    }

    public function testActivate()
    {
        $genre = new Genre(
            name: 'teste',
            isActive: false,
        );

        $this->assertFalse($genre->isActive);

        $genre->activate();

        $this->assertTrue($genre->isActive);
    }

    public function testUpdate()
    {
        $genre = new Genre(
            name: 'teste'
        );

        $this->assertEquals('teste', $genre->name);

        $genre->update(
            name: 'Name Updated'
        );

        $this->assertEquals('Name Updated', $genre->name);
    }

    public function testEntityException()
    {
        $this->expectException(EntityValidationException::class);

        $genre = new Genre(
            name: 's',
        );
    }

    public function testEntityUpdateException()
    {
        $this->expectException(EntityValidationException::class);

        $uuid =  (string) \Core\Domain\ValueObjects\Uuid::generate();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id:  \Core\Domain\ValueObjects\Uuid::new($uuid),
            name: 'New Genre',
            isActive: false,
            createdAt: new DateTime($date),
        );

        $genre->update(
            name: 's'
        );
    }

    public function testAddCategoryToGenrre()
    {
        $categoryId =  (string) \Core\Domain\ValueObjects\Uuid::generate();

        $genre = new Genre(
            name: 'new genre'
        );

        $this->assertIsArray($genre->categoriesId);
        $this->assertCount(0, $genre->categoriesId);

        $genre->addCategory(
            categoryId: $categoryId
        );
        $genre->addCategory(
            categoryId: $categoryId
        );
        $this->assertCount(2, $genre->categoriesId);
    }

    public function testRemoveCategoryToGenrre()
    {
        $categoryId = (string) \Core\Domain\ValueObjects\Uuid::generate();
        $categoryId2 = (string) \Core\Domain\ValueObjects\Uuid::generate();

        $genre = new Genre(
            name: 'new genre',
            categoriesId: [
                $categoryId,
                $categoryId2,
            ]
        );
        $this->assertCount(2, $genre->categoriesId);

        $genre->removeCategory(
            categoryId: $categoryId,
        );

        $this->assertCount(1, $genre->categoriesId);
        $this->assertEquals($categoryId2, $genre->categoriesId[1]);
    }
}
