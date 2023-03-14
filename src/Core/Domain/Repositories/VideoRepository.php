<?php

declare(strict_types=1);

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Video;

/**
 * @extends Repository<Video>
 */
interface VideoRepository extends Repository
{
    public function updateMedia(Video $video): void;
}
