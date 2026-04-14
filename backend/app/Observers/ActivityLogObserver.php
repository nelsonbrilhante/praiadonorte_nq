<?php

namespace App\Observers;

use App\Models\ActivityLog;

class ActivityLogObserver
{
    public function creating(ActivityLog $activity): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            $activity->ip_address = $activity->ip_address ?? null;
            $activity->user_agent = $activity->user_agent ?? 'artisan/cli';

            return;
        }

        if (function_exists('request') && request()) {
            $activity->ip_address = $activity->ip_address ?? request()->ip();
            $activity->user_agent = $activity->user_agent ?? request()->userAgent();
        }
    }
}
