<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surfboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'surfer_id',
        'brand',
        'model',
        'length',
        'image',
        'specs',
        'order',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function surfer(): BelongsTo
    {
        return $this->belongsTo(Surfer::class);
    }
}
