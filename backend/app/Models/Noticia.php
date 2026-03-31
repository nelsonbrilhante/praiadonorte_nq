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
        'show_in_hero',
        'published_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'tags' => 'array',
        'featured' => 'boolean',
        'show_in_hero' => 'boolean',
        'published_at' => 'datetime',
    ];
}
