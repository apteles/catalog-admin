<?php

namespace Core\Application\UseCases\CastMember\Create;

class Input
{
    public function __construct(
        public string $name,
        public int $type,
    ) {
    }
}
