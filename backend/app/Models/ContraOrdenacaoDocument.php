<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class ContraOrdenacaoDocument extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'description', 'file', 'icon', 'order', 'published_at'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'published_at' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Doc. Contraordenação criado',
                'updated' => 'Doc. Contraordenação atualizado',
                'deleted' => 'Doc. Contraordenação eliminado',
                default => "Doc. C.O. {$eventName}",
            });
    }
}
