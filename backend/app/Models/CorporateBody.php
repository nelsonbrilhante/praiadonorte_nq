<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class CorporateBody extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'role', 'section', 'photo', 'cv_file', 'order', 'published'];

    protected $casts = [
        'role' => 'array',
        'published' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Órgão Social criado',
                'updated' => 'Órgão Social atualizado',
                'deleted' => 'Órgão Social eliminado',
                default => "Órgão Social {$eventName}",
            });
    }
}
