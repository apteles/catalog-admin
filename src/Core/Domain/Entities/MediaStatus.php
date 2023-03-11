<?php
declare(strict_types=1);

namespace Core\Domain\Entities;

enum MediaStatus : int
{
    case PROCESSING = 0;
    case COMPLETE = 1;
    case PENDING = 2;
}
