<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Genre;

/**
 * @extends Repository<Genre>
 */
interface GenreRepository extends Repository
{
    public function getIdsListIds(array $genresIds = []): array;
}
