<?php

declare(strict_types=1);

namespace Core\Application\UseCases\Genre\Delete;

class Output
{
    public function __construct(
        public readonly bool $success
    ) {
    }
}
