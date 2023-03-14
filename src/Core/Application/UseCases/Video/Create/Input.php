<?php
declare(strict_types=1);

namespace Core\Application\UseCases\Video\Create;

use Core\Domain\Entities\Rating;

class Input
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly int $yearLaunched,
        public readonly int $duration,
        public readonly bool $opened,
        public readonly Rating $rating,
        public readonly array $categories = [],
        public readonly array $genres = [],
        public readonly array $castMembers = [],
        public readonly ?array $videoFile = null,
        public readonly ?array $trailerFile = null,
        public readonly ?array $thumbFile = null,
        public readonly ?array $thumbHalf = null,
        public readonly ?array $bannerFile = null,
    ){
    }
}
