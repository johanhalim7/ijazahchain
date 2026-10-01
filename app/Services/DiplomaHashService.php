<?php

namespace App\Services;

use App\Models\Ijazah;
use Carbon\CarbonInterface;

class DiplomaHashService
{
    public function canonicalPayload(array|Ijazah $data): array
    {
        $isModel = $data instanceof Ijazah;
        $get = function($key) use ($data, $isModel) {
            return $isModel ? $data->{$key} : ($data[$key] ?? null);
        };

        return [
            'nama' => strtoupper(trim((string) $get('nama'))),
            'nim' => trim((string) $get('nim')),
            'nik' => trim((string) $get('nik')),
            'tempat_tanggal_lahir' => strtoupper(trim((string) $get('tempat_tanggal_lahir'))),
            'nama_institusi' => strtoupper(trim((string) $get('nama_institusi'))),
            'fakultas' => strtoupper(trim((string) $get('fakultas'))),
            'prodi' => strtoupper(trim((string) $get('prodi'))),
            'gelar' => strtoupper(trim((string) $get('gelar'))),
            'nomor_ijazah' => strtoupper(trim((string) $get('nomor_ijazah'))),
            'tanggal_lulus' => $this->dateOnly($get('tanggal_lulus')),
            'tanggal_diberikan' => $this->dateOnly($get('tanggal_diberikan')),
            'version' => (int) ($get('version') ?: 1),
        ];
    }

    public function hash(Ijazah $ijazah): string
    {
        $payload = $this->canonicalPayload($ijazah);
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // Composite Hash: SHA-256(file_hash + data_json)
        return hash('sha256', ($ijazah->file_hash ?? '') . $json);
    }

    private function dateOnly(mixed $value): string
    {
        if ($value instanceof CarbonInterface) {
            return $value->format('Y-m-d');
        }

        $value = (string) $value;
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            return substr($value, 0, 10);
        }

        return $value;
    }

    public function typedData(Ijazah $ijazah, string $action): array
    {
        return [
            'domain' => [
                'name' => 'IjazahChain',
                'version' => '1',
                'chainId' => (int) config('blockchain.chain_id'),
                'verifyingContract' => config('blockchain.contract_address') ?: '0x0000000000000000000000000000000000000000',
            ],
            'types' => [
                'EIP712Domain' => [
                    ['name' => 'name', 'type' => 'string'],
                    ['name' => 'version', 'type' => 'string'],
                    ['name' => 'chainId', 'type' => 'uint256'],
                    ['name' => 'verifyingContract', 'type' => 'address'],
                ],
                'DiplomaApproval' => [
                    ['name' => 'action', 'type' => 'string'],
                    ['name' => 'nomorIjazah', 'type' => 'string'],
                    ['name' => 'diplomaHash', 'type' => 'string'],
                    ['name' => 'version', 'type' => 'uint256'],
                ],
            ],
            'primaryType' => 'DiplomaApproval',
            'message' => [
                'action' => $action,
                'nomorIjazah' => $ijazah->nomor_ijazah,
                'diplomaHash' => $ijazah->hash,
                'version' => (int) $ijazah->version,
            ],
        ];
    }
}
