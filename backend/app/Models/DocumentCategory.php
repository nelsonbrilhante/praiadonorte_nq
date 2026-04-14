<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class DocumentCategory extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'slug', 'description', 'order'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class)->orderBy('order')->orderByDesc('published_at');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Categoria de documentos criada',
                'updated' => 'Categoria de documentos atualizada',
                'deleted' => 'Categoria de documentos eliminada',
                default => "Categoria {$eventName}",
            });
    }
}
