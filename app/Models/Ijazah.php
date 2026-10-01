<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ijazah extends Model
{
    protected $fillable = [
        'nim', 'nomor_ijazah', 'payload_data', 'file_path', 'file_hash',
        'hash', 'status', 'approval_data', 'blockchain_status', 
        'blockchain_data', 'version', 'created_by', 'workflow_id', 'current_approver_role',
    ];

    protected $casts = [
        'payload_data' => 'array',
        'approval_data' => 'array',
        'blockchain_data' => 'array',
    ];

    public function getAttribute($key)
    {
        $payloadKeys = ['nama', 'nik', 'tempat_tanggal_lahir', 'nama_institusi', 'fakultas', 'prodi', 'gelar', 'tanggal_lulus', 'tanggal_diberikan'];
        if (in_array($key, $payloadKeys)) {
            $value = $this->payload_data[$key] ?? null;
            if ($value && in_array($key, ['tanggal_lulus', 'tanggal_diberikan'])) {
                return \Carbon\Carbon::parse($value);
            }
            return $value;
        }

        $approvalKeys = ['catatan', 'approved_akademik_at', 'approved_rektor_at', 'approved_admin_at', 'approved_akademik_by', 'approved_rektor_by', 'approved_admin_by', 'rejected_by', 'rejected_role', 'rejected_at', 'signatures'];
        if (in_array($key, $approvalKeys)) {
            $value = $this->approval_data[$key] ?? null;
            if ($value && str_ends_with($key, '_at')) {
                return \Carbon\Carbon::parse($value);
            }
            return $value;
        }

        $blockchainKeys = ['tx_hash', 'block_number'];
        if (in_array($key, $blockchainKeys)) {
            return $this->blockchain_data[$key] ?? null;
        }

        if ($key === 'qr_code' && $this->nomor_ijazah && $this->hash && $this->blockchain_status === 'Uploaded') {
            return route('verify.form', ['nomor_ijazah' => $this->nomor_ijazah, 'hash' => $this->hash]);
        }

        return parent::getAttribute($key);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function revokeRequests(): HasMany
    {
        return $this->hasMany(RevokeRequest::class);
    }
}
