<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
