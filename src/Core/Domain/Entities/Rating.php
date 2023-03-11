<?php
declare(strict_types=1);

namespace Core\Domain\Entities;

enum Rating : string
{
    case ER = 'ER';
    case L = 'L';
    case RATE10 = '10';
    case RATE12 = '12';
    case RATE14 = '14';
    case RATE16 = '16';
    case RATE18 = '18';
}
