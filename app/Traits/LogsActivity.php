<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public function logActivity(string $activity, ?string $description = null, array $properties = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'properties' => $properties,
        ]);
    }
}
