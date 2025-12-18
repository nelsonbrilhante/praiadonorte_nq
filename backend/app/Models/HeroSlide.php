<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlide extends Model
{
    protected $fillable = [
        'pagina_id',
        'order',
        'video_url',
        'fallback_image',
        'is_live',
        'audio_enabled',
        'hero_logo',
        'use_logo_as_title',
        'logo_height',
        'title',
        'subtitle',
        'cta_text',
        'cta_url',
        'active',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'cta_text' => 'array',
        'cta_url' => 'array',
        'is_live' => 'boolean',
        'audio_enabled' => 'boolean',
        'use_logo_as_title' => 'boolean',
        'active' => 'boolean',
    ];

    public function pagina(): BelongsTo
    {
        return $this->belongsTo(Pagina::class);
    }
}
