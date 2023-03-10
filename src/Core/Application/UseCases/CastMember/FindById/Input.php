<?php

namespace  Core\Application\UseCases\CastMember\FindById;

class Input
{
    public function __construct(
        public string $id,
    ) {
    }
}
