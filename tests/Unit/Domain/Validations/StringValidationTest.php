<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Validations;

use Core\Domain\Validations\Str;
use PHPUnit\Framework\TestCase;

class StringValidationTest extends TestCase
{
    public function testItShouldValidateIfValuePassedInIsEmpty(): void
    {
        $value = '';
        $this->assertTrue(Str::create($value)->isEmpty());
    }
    public function testItShouldValidateIfValuePassedInIsNotEmpty(): void
    {
        $value = 'content';
        $this->assertTrue(Str::create($value)->isNotEmpty());
    }

    public function testItShouldValidateMinValue(): void
    {
        $value = 'c';
        $this->assertFalse(Str::create($value)->minLength(2));
        $value = 'foo';
        $this->assertTrue(Str::create($value)->minLength(2));
    }

    public function testItShouldValidateMaxValue(): void
    {
        $value = str_repeat('foo', 200);
        $this->assertFalse(Str::create($value)->maxLength(255));
    }
}