<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Surfer extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Surfer criado',
                'updated' => 'Surfer atualizado',
                'deleted' => 'Surfer eliminado',
                default => "Surfer {$eventName}",
            });
    }
}
