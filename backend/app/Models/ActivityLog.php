<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class ActivityLog extends SpatieActivity
{
    /**
     * Additional attributes that are mass assignable beyond spatie defaults.
     */
    public $guarded = [];
}
