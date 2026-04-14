<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Document extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['document_category_id', 'title', 'file', 'published_at', 'order'];

    protected $casts = [
        'title' => 'array',
        'published_at' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Documento criado',
                'updated' => 'Documento atualizado',
                'deleted' => 'Documento eliminado',
                default => "Documento {$eventName}",
            });
    }
}
