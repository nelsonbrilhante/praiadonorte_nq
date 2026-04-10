<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContraOrdenacaoDocument extends Model
{
    protected $fillable = ['title', 'description', 'file', 'icon', 'order', 'published_at'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'published_at' => 'date',
    ];
}
