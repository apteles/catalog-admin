<?php
declare(strict_types=1);

namespace Core\Domain\Factories\Validations;


use Core\Domain\Validations\Validation;
use Core\Shared\Domain\Validations\VideoLaravel;

class Video
{
    public static function create(): Validation
    {
        return new VideoLaravel();
    }
}
