<?php
declare(strict_types=1);

namespace Core\Application\UseCases\Video\Create;

use Core\Domain\Entities\MediaStatus;
use Core\Domain\Entities\Video;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Events\VideoEventManager;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repositories\CastMemberRepository;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\Repositories\GenreRepository;
use Core\Domain\Repositories\VideoRepository;
use Core\Domain\ValueObjects\Media;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Repository\Transaction;
use Core\Shared\Storage;

class CreateVideoUseCase
{

    public function __construct(
        private readonly VideoRepository $repository,
        private readonly CategoryRepository $categoryRepository,
        private readonly GenreRepository $genreRepository,
        private readonly CastMemberRepository $castMemberRepository,
        private readonly Transaction $transaction,
        private readonly Storage $storage,
        private readonly VideoEventManager $eventManager,
    )    {
    }

    /**
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function execute(Input $input): Output
    {
            $video = new Video(
                title: $input->title,
                description: $input->description,
                yearLaunched: $input->yearLaunched,
                duration: $input->duration,
                opened: $input->opened,
                rating: $input->rating,
                id: Uuid::generate(),
            );

            $this->validateCategoriesId($input->categories);
            $this->validateCastMembersId($input->castMembers);
            $this->validateGenresId($input->genres);

            foreach ($input->categories as $categoryId) {
                $video->addCategoryId($categoryId);
            }

            foreach ($input->genres as $genreId) {
                $video->addGenre($genreId);
            }

            foreach ($input->castMembers as $castMemberId) {
                $video->addCastMember($castMemberId);
            }

        try {
                $this->repository->create($video);
                if($path = $this->storeMedia($video->id(), $input->videoFile)) {
                    $video->changeVideoFile(new Media(
                        filePath: $path,
                        mediaStatus: MediaStatus::PROCESSING,
                    ));
                    $this->repository->updateMedia($video);
                    $this->eventManager->dispatch(
                        new VideoCreatedEvent($video)
                    );
                }

                $this->transaction->commit();

                return $this->output($video);

        }catch (\Throwable $th) {
                dd($th);
                $this->transaction->rollback();

            throw $th;
        }
    }

    private function storeMedia($path, ?array $media = null): ?string
    {
        if(!empty($media)) {
            return $this->storage->store($path, $media);
        }
        return null;
    }

    /**
     * @throws NotFoundException
     */
    private function validateCategoriesId(array $categoriesId = []): void
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    /**
     * @throws NotFoundException
     */
    private function validateGenresId(array $genresId = []): void
    {
        $genresDb = $this->genreRepository->getIdsListIds($genresId);

        $arrayDiff = array_diff($genresId, $genresDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    /**
     * @throws NotFoundException
     */
    private function validateCastMembersId(array $membersId = []): void
    {
        $castMembersDb = $this->castMemberRepository->getIdsListIds($membersId);

        $arrayDiff = array_diff($membersId, $castMembersDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'CastMembers' : 'CastMember',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function output(Video $entity): Output
    {

        return new Output(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            createdAt: $entity->createdAt(),
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMemberIds,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path(),
        );
    }
}
