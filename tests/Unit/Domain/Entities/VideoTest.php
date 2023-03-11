<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use Core\Domain\Entities\MediaStatus;
use Core\Domain\Entities\Rating;
use Core\Domain\Entities\Video;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\ValueObjects\Image;
use Core\Domain\ValueObjects\Media;
use Core\Domain\ValueObjects\Uuid;
use DateTime;
use Tests\TestCase;

class VideoTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            id: Uuid::new($uuid),
            published: true,
            createdAt: new DateTime(date('Y-m-d H:i:s')),
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('new title', $entity->title);
        $this->assertEquals('description', $entity->description);
        $this->assertEquals(2029, $entity->yearLaunched);
        $this->assertEquals(12, $entity->duration);
        $this->assertEquals(true, $entity->opened);
        $this->assertEquals(true, $entity->published);
    }

    public function testIdAndCreatedAt()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );

        $this->assertNotEmpty($entity->id());
        $this->assertNotEmpty($entity->createdAt());
    }

    public function testAddCategoryId()
    {
        $categoryId = (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );

        $this->assertCount(0, $entity->categoriesId);
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $this->assertCount(2, $entity->categoriesId);
    }

    public function testRemoveCategoryId()
    {
        $categoryId =  (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );
        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: 'uuid',
        );
        $this->assertCount(2, $entity->categoriesId);

        $entity->removeCategoryId(
            categoryId: $categoryId,
        );
        $this->assertCount(1, $entity->categoriesId);
    }

    public function testAddGenre()
    {
        $genreId =  (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );

        $this->assertCount(0, $entity->genresId);
        $entity->addGenre(
            genreId: $genreId,
        );
        $entity->addGenre(
            genreId: $genreId,
        );
        $this->assertCount(2, $entity->genresId);
    }

    public function testRemoveGenre()
    {
        $genreId =  (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );
        $entity->addGenre(
            genreId: $genreId,
        );
        $entity->addGenre(
            genreId: 'uuid',
        );
        $this->assertCount(2, $entity->genresId);

        $entity->removeGenre(
            genreId: $genreId,
        );
        $this->assertCount(1, $entity->genresId);
    }

    public function testAddCastMember()
    {
        $castMemberId =  (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );

        $this->assertCount(0, $entity->castMemberIds);
        $entity->addCastMember(
            castMemberId: $castMemberId,
        );
        $entity->addCastMember(
            castMemberId: $castMemberId,
        );
        $this->assertCount(2, $entity->castMemberIds);
    }

    public function testRemoveCastMember()
    {
        $castMemberId =  (string) Uuid::generate();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );
        $entity->addCastMember(
            castMemberId: $castMemberId,
        );
        $entity->addCastMember(
            castMemberId: 'uuid',
        );
        $this->assertCount(2, $entity->castMemberIds);

        $entity->removeCastMember(
            castMemberId: $castMemberId,
        );
        $this->assertCount(1, $entity->castMemberIds);
    }

    public function testValueObjectImage()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbFile: new Image('teste-path/image.png'),
        );

        $this->assertNotNull($entity->thumbFile());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('teste-path/image.png', $entity->thumbFile()->path());
    }

    public function testValueObjectImageToThumbHalf()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            thumbHalf: new Image('teste-path/image.png'),
        );

        $this->assertNotNull($entity->thumbHalf());
        $this->assertInstanceOf(Image::class, $entity->thumbHalf());
        $this->assertEquals('teste-path/image.png', $entity->thumbHalf()->path());
    }

    public function testValueObjectImageToBannerFile()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            bannerFile: new Image('teste-path/banner.png'),
        );

        $this->assertNotNull($entity->bannerFile());
        $this->assertInstanceOf(Image::class, $entity->bannerFile());
        $this->assertEquals('teste-path/banner.png', $entity->bannerFile()->path());
    }

    public function testValueObjectMedia()
    {
        $trailerFile = new Media(
            filePath: 'path/trailer.mp4',
            mediaStatus: MediaStatus::PENDING,
            encodedPath: 'path/encoded.extension',
        );

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            trailerFile: $trailerFile,
        );

        $this->assertNotNull($entity->trailerFile());
        $this->assertInstanceOf(Media::class, $entity->trailerFile());
        $this->assertEquals('path/trailer.mp4', $entity->trailerFile()->filePath);
    }

    public function testValueObjectMediaVideo()
    {
        $videoFile = new Media(
            filePath: 'path/video.mp4',
            mediaStatus: MediaStatus::COMPLETE,
        );

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            videoFile: $videoFile,
        );

        $this->assertNotNull($entity->videoFile());
        $this->assertInstanceOf(Media::class, $entity->videoFile());
        $this->assertEquals('path/video.mp4', $entity->videoFile()->filePath);
    }

    public function testException()
    {
        $this->expectException(EntityValidationException::class);

        new Video(
            title: '',
            description: '',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
        );
    }
}
