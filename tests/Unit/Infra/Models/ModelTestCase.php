<?php
declare(strict_types=1);

namespace Tests\Unit\Infra\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    private bool $assertIncrementDisabled = true;

    public function testItShouldKeepAutoIncrementFalse(): void
    {
        if($this->assertIncrementDisabled) {
            $this->assertFalse(($this->model())->incrementing);
        }
    }

    public function testItShouldUseTraitsRequired()
    {
        $traitsUsedByCategory = array_values(class_uses($this->model()));
        $this->assertEquals($this->traits(), $traitsUsedByCategory);
    }

    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillables(): array;
}
