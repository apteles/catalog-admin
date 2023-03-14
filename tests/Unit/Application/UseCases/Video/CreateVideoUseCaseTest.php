<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Video;

use Core\Application\UseCases\Video\Create\CreateVideoUseCase;
use Core\Application\UseCases\Video\Create\Input;
use Core\Domain\Entities\Rating;
use Core\Domain\Events\VideoEventManager;
use Core\Domain\Repositories\CastMemberRepository;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\Repositories\VideoRepository;
use Core\Shared\Repository\Transaction;
use Core\Shared\Storage;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseTest extends TestCase
{
    public function testItShouldCreateANewVideo(): void
    {
        $categoriesIds = ['7','8','9'];
        $castMembersIds = ['4','5','6'];
        $genresIds = ['1','2','3'];

        $repositoryMock = m::mock(stdClass::class, VideoRepository::class);
        $repositoryMock->shouldReceive('create')->once();
        $repositoryMock->shouldReceive('updateMedia')->once();
        $categoryRepositoryMock = m::mock(stdClass::class, CategoryRepository::class);
        $categoryRepositoryMock->shouldReceive('getIdsListIds')->once()->andReturn($categoriesIds);
        $genreRepositoryMock = m::mock(stdClass::class, GenreRepository::class);
        $genreRepositoryMock->shouldReceive('getIdsListIds')->once()->andReturn($genresIds);
        $castMemberRepositoryMock = m::mock(stdClass::class, CastMemberRepository::class);
        $castMemberRepositoryMock->shouldReceive('getIdsListIds')->once()->andReturn($castMembersIds);
        $transactionMock = m::mock(stdClass::class, Transaction::class);
        $transactionMock->shouldReceive('commit')->once();

        $storageMock = m::mock(stdClass::class, Storage::class);
        $storageMock->shouldReceive('store')->once()->andReturn('path');
        $eventMock = m::mock(stdClass::class, VideoEventManager::class);
        $eventMock->shouldReceive('dispatch')->once();
        $useCase = new CreateVideoUseCase(
            $repositoryMock,
            $categoryRepositoryMock,
            $genreRepositoryMock,
            $castMemberRepositoryMock,
            $transactionMock,
            $storageMock,
            $eventMock,
        );

        $output = $useCase->execute(
            new Input(
                title: 'test',
                description: 'test',
                yearLaunched: 2019,
                duration: 120,
                opened: true,
                rating: Rating::RATE10,
                categories: $categoriesIds,
                genres: $genresIds,
                castMembers: $castMembersIds,
                videoFile: [1,2,3],
            )
        );

        $this->assertNotEmpty($output->id);
        m::close();
    }
}

