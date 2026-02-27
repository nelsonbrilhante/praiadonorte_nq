<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'start_date',
        'end_date',
        'location',
        'entity',
        'category',
        'image',
        'gallery',
        'ticket_url',
        'video_url',
        'schedule',
        'partners',
        'featured',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'excerpt' => 'array',
        'schedule' => 'array',
        'gallery' => 'array',
        'partners' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'featured' => 'boolean',
    ];
}
