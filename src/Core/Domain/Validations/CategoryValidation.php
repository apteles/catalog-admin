<?php
declare(strict_types=1);

namespace Core\Domain\Validations;

use Core\Domain\Exceptions\CategoryException;

class CategoryValidation
{
    public static function isEmpty(string $value, string $exceptionMessage): bool
    {
        return Str::create($value)->isNotEmpty() ?: throw new CategoryException($exceptionMessage);
    }

    public static function min(string $value, int $minLength, string $exceptionMessage): bool
    {
        return Str::create($value)->minLength($minLength) ?: throw new CategoryException($exceptionMessage);
    }

    public static function max(string $value, int $maxLength, string $exceptionMessage): bool
    {
        return Str::create($value)->maxLength($maxLength) ?: throw new CategoryException($exceptionMessage);
    }
}