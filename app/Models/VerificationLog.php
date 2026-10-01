<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    protected $fillable = [
        'verification_type', 'input', 'hash', 'blockchain_hash', 'tx_hash', 'result',
        'ip_address', 'user_agent', 'verified_at',
    ];

    protected $casts = ['verified_at' => 'datetime'];
}
