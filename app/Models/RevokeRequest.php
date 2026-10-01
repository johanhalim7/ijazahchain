<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevokeRequest extends Model
{
    protected $fillable = [
        'ijazah_id', 'workflow_id', 'requested_by', 'reason', 'status', 'signatures',
        'approved_by', 'approved_at', 'tx_hash', 'current_approver_role'
    ];

    protected $casts = [
        'signatures' => 'array',
        'approved_at' => 'datetime',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function ijazah(): BelongsTo
    {
        return $this->belongsTo(Ijazah::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
