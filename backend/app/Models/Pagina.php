<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pagina extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'video_url',
        'is_live',
        'audio_enabled',
        'hero_logo',
        'hero_use_logo',
        'hero_logo_height',
        'slider_interval',
        'slider_autoplay',
        'entity',
        'seo_title',
        'seo_description',
        'published',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'seo_title' => 'array',
        'seo_description' => 'array',
        'published' => 'boolean',
        'is_live' => 'boolean',
        'audio_enabled' => 'boolean',
        'hero_use_logo' => 'boolean',
        'slider_autoplay' => 'boolean',
    ];

    /**
     * Get the hero slides for this page.
     */
    public function heroSlides(): HasMany
    {
        return $this->hasMany(HeroSlide::class)->orderBy('order');
    }

    /**
     * Check if any hero slide is currently live.
     */
    public function hasAnyLiveSlide(): bool
    {
        return $this->heroSlides()->where('is_live', true)->where('active', true)->exists();
    }
}
