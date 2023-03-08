<?php

declare(strict_types=1);

namespace Core\Domain\Entities;

use Core\Domain\Validations\DomainValidation;
use Core\Domain\ValueObjects\Uuid;
use DateTime;
use DateTimeInterface;
use Exception;

final class Category
{
    /**
     * @throws Exception
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected Uuid|string $id = '',
        protected CategoryStatus $status = CategoryStatus::ACTIVE,
        protected DateTimeInterface|string $createdAt = '',
    ) {
        $this->id = $this->id ? Uuid::new((string)$this->id) : Uuid::generate();
        $this->setCreatedAt($this->createdAt);
        $this->validate();
    }

    /**
     * @param DateTime|string $createdAt
     * @return void
     * @throws Exception
     */
    private function setCreatedAt(DateTimeInterface|string $createdAt): void
    {
        $isValidString = is_string($createdAt) && !empty($createdAt);
        $this->createdAt = $isValidString ? new DateTime($createdAt) : new DateTime();
    }

    /**
     * @param string $name
     * @param string $description
     * @return Category
     * @throws Exception
     */
    public static function new(string $name, string $description): Category
    {
        return new static(
            name: $name,
            description: $description
        );
    }

    /**
     * @throws Exception
     */
    public static function newWithID(Uuid|string $id, string $name, string $description): Category
    {
        return new static(
            name: $name,
            description: $description,
            id: $id
        );
    }

    public function id(): Uuid|string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $aName): void
    {
        $this->name = $aName;

        $this->validate();
    }

    public function description(): string
    {
        return $this->description;
    }

    public function changeDescription(string $aDescription): void
    {
        $this->description = $aDescription;
        $this->validate();
    }

    public function status(): CategoryStatus
    {
        return $this->status;
    }

    public function createdAt(): DateTimeInterface
    {
        return is_string($this->createdAt) ? new DateTime($this->createdAt) : $this->createdAt;
    }

    public function update(string $name, string $description): void
    {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    public function activate(): CategoryStatus
    {
        $this->status = CategoryStatus::ACTIVE;
        return $this->status;
    }

    public function deactivate(): CategoryStatus
    {
        $this->status = CategoryStatus::INACTIVE;
        return $this->status;
    }

    private function validate(): void
    {
        DomainValidation::isEmpty(
            value: $this->name,
            exceptionMessage: 'Empty name is not allowed.'
        );
        DomainValidation::min(
            value: $this->name,
            minLength: 2,
            exceptionMessage:'Name must have at least more than 2 characters.'
        );
        DomainValidation::max(
            $this->name,
            maxLength: 255,
            exceptionMessage: 'Name should have less than 255 characters.'
        );

        DomainValidation::isEmpty(
            value: $this->description,
            exceptionMessage: 'Empty description is not allowed.'
        );
        DomainValidation::min(
            value: $this->description,
            minLength: 2,
            exceptionMessage:'Description must have at least more than 2 characters.'
        );
        DomainValidation::max(
            $this->description,
            maxLength: 255,
            exceptionMessage: 'Description should have less than 255 characters.'
        );
    }

}
