<?php

namespace Core\Application\UseCases\CastMember\FindById;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at,
    ) {
    }
}
