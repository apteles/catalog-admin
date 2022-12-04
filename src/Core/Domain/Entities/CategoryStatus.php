<?php
declare(strict_types=1);

namespace Core\Domain\Entities;

enum CategoryStatus : int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
}
