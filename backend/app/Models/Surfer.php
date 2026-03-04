<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'aka',
        'slug',
        'bio',
        'quote',
        'photo',
        'social_media',
        'board_image',
        'featured',
        'order',
    ];

    protected $casts = [
        'bio' => 'array',
        'quote' => 'array',
        'social_media' => 'array',
        'featured' => 'boolean',
    ];
}
