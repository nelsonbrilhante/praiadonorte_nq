<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'photo',
        'nationality',
        'achievements',
        'social_media',
        'featured',
        'order',
    ];

    protected $casts = [
        'bio' => 'array',
        'achievements' => 'array',
        'social_media' => 'array',
        'featured' => 'boolean',
    ];

    public function surfboards(): HasMany
    {
        return $this->hasMany(Surfboard::class)->orderBy('order');
    }
}
