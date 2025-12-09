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
        'start_date',
        'end_date',
        'location',
        'entity',
        'image',
        'ticket_url',
        'featured',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'featured' => 'boolean',
    ];
}
