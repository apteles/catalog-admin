<?php

namespace Core\Domain\Entities;

use Core\Domain\Entities\Traits\MethodsMagicsTrait;
use Core\Domain\Validations\DomainValidation;
use Core\Domain\ValueObjects\Uuid;
use DateTime;

class CastMember
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected CastMemberType $type,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::generate();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    protected function validate()
    {
        DomainValidation::min(
            value: $this->name,
            minLength: 3,
            exceptionMessage: 'Name must have at least more than 2 characters.'
        );

        DomainValidation::max(
            $this->name,
            maxLength: 255,
            exceptionMessage: 'Name should have less than 255 characters.'
        );
    }
}
