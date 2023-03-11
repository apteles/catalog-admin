<?php
declare(strict_types=1);

namespace Core\Domain\Validations;

use Core\Domain\Entities\Entity;

interface Validation
{
    public function validate(Entity $entity): void;
}
