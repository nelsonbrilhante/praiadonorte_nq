<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Evento extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Evento criado',
                'updated' => 'Evento atualizado',
                'deleted' => 'Evento eliminado',
                default => "Evento {$eventName}",
            });
    }
}
