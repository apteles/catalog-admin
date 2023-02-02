<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property string $created_at
 */
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'status' => 'boolean',
        'deleted_at' => 'datetime'
    ];
}
