<?php

namespace Tests\Unit\Infra\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTest extends ModelTestCase
{

    protected function model(): Model
    {
        return new Category();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class
        ];
    }
    protected function fillables(): array
    {
        return [];
    }
}
