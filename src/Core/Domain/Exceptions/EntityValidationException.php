<?php

declare(strict_types=1);

namespace Core\Domain\Exceptions;

use InvalidArgumentException;

class EntityValidationException extends InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
