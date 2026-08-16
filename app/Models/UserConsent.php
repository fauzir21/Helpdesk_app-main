<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConsent extends Model
{
    protected $table = 'tb_user_consents';

    protected $fillable = [
        'user_id',
        'consent_date',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'consent_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
