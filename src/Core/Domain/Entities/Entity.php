<?php

declare(strict_types=1);

namespace Core\Domain\Entities;


use Core\Domain\Entities\Traits\MethodsMagicsTrait;
use Core\Domain\Validations\DomainValidation;
use Core\Domain\ValueObjects\Image;
use Core\Domain\ValueObjects\Media;
use Core\Domain\ValueObjects\Uuid;
use Core\Shared\Domain\Notification\Notification;
use DateTime;
use Exception;

abstract class Entity
{
    protected ?Notification $notification = null;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function __get($property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new Exception("Property {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

}
