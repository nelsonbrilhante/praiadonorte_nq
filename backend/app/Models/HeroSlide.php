<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class HeroSlide extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Hero Slide criado',
                'updated' => 'Hero Slide atualizado',
                'deleted' => 'Hero Slide eliminado',
                default => "Hero Slide {$eventName}",
            });
    }
}
