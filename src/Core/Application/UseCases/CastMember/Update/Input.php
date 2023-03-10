<?php

namespace Core\Application\UseCases\CastMember\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
