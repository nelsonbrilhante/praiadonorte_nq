<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class ContactMessage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'entity',
        'type',
        'name',
        'email',
        'phone',
        'message',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Mensagem de contacto recebida',
                'updated' => 'Mensagem de contacto atualizada',
                'deleted' => 'Mensagem de contacto eliminada',
                default => "Mensagem {$eventName}",
            });
    }
}
