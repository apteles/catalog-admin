<?php

namespace Core\Application\UseCases\CastMember\Delete;

class Input
{
    public function __construct(
        public string $id,
    ) {
    }
}
