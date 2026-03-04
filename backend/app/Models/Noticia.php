<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'author',
        'category',
        'entity',
        'tags',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'tags' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];
}
