<?php
declare(strict_types=1);

namespace Core\Domain\Entities;

use Core\Domain\Entities\Traits\MethodsMagicsTrait;
use Core\Domain\Validations\DomainValidation;
use Core\Domain\ValueObjects\Uuid;
use DateTime;
use DateTimeInterface;

class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected $isActive = true,
        protected array $categoriesId = [],
        protected ?DateTimeInterface $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::generate();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    protected function validate(): void
    {
        DomainValidation::min(
            value: $this->name,
            minLength: 2,exceptionMessage: 'Name must have at least more than 2 characters.'
        );

        DomainValidation::max(
            $this->name,
            maxLength: 255,
            exceptionMessage: 'Name should have less than 255 characters.'
        );

    }
}
