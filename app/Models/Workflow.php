<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'name',
        'steps',
        'is_active'
    ];

    protected $casts = [
        'steps' => 'array',
        'is_active' => 'boolean'
    ];
}
