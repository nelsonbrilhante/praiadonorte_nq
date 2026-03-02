<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateBody extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'section', 'photo', 'cv_file', 'order', 'published'];

    protected $casts = [
        'role' => 'array',
        'published' => 'boolean',
    ];
}
