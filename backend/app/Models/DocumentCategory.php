<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'order'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class)->orderBy('order')->orderByDesc('published_at');
    }
}
