<?php

namespace Tests\Feature;

use App\Models\Ijazah;
use App\Models\RevokeRequest;
use App\Models\User;
use App\Services\DiplomaHashService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IjazahWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_publish_verify_and_revoke_workflow(): void
    {
        $akademik = $this->user('akademik');
        $rektor = $this->user('rektor');
        $admin = $this->user('admin');

        $payload = [
            'nama' => 'NAMA PEMILIK IJAZAH',
            'nim' => '211000001',
            'tempat_lahir' => 'JAKARTA',
            'tanggal_lahir' => '2001-01-15',
            'nama_institusi' => 'UNIVERSITAS IJAZAHCHAIN',
            'fakultas' => 'FAKULTAS TEKNOLOGI INFORMASI',
            'prodi' => 'SISTEM INFORMASI',
            'gelar' => 'S.Kom',
            'nomor_ijazah' => 'IC-TEST-0001',
            'tanggal_lulus' => '2026-06-30',
            'ipk' => '3.82',
        ];

        $this->actingAs($akademik)->post(route('ijazahs.store'), $payload)->assertRedirect();
        $ijazah = Ijazah::firstOrFail();
        $this->assertSame('Draft', $ijazah->status);

        $this->actingAs($akademik)->post(route('approvals.approve', $ijazah), $this->signature())->assertRedirect();
        $this->assertSame('Pending Rektor', $ijazah->fresh()->status);

        $this->actingAs($rektor)->post(route('approvals.approve', $ijazah), $this->signature())->assertRedirect();
        $this->assertSame('Pending Admin', $ijazah->fresh()->status);

        $this->actingAs($admin)->post(route('approvals.approve', $ijazah), $this->signature())->assertRedirect();
        $this->actingAs($admin)->post(route('approvals.upload', $ijazah), [
            'tx_hash' => '0x'.str_repeat('a', 64),
            'block_number' => 123,
        ])->assertRedirect();
        $this->assertSame('Aktif', $ijazah->fresh()->status);

        $this->post(route('verify.submit'), $payload + ['method' => 'data'])->assertOk()->assertSee('VALID');

        $this->actingAs($admin)->post(route('revoke.store'), [
            'ijazah_id' => $ijazah->id,
            'reason' => 'Kesalahan administratif.',
        ])->assertRedirect();
        $revoke = RevokeRequest::firstOrFail();
        $this->actingAs($admin)->post(route('revoke.execute', $revoke), [
            'tx_hash' => '0x'.str_repeat('b', 64),
        ])->assertForbidden();

        $this->actingAs($rektor)->post(route('revoke.approve', $revoke), $this->signature())->assertRedirect();
        $this->actingAs($akademik)->post(route('revoke.approve', $revoke), $this->signature())->assertRedirect();
        $this->actingAs($admin)->post(route('revoke.approve', $revoke), $this->signature())->assertRedirect();
        $this->actingAs($admin)->post(route('revoke.execute', $revoke), [
            'tx_hash' => '0x'.str_repeat('c', 64),
        ])->assertRedirect();

        $this->assertSame('Revoked', $ijazah->fresh()->status);
        $this->post(route('verify.submit'), ['method' => 'hash', 'hash' => $ijazah->hash])->assertOk()->assertSee('REVOKED');
    }

    public function test_hash_detects_local_data_manipulation(): void
    {
        $akademik = $this->user('akademik');
        $hashService = app(DiplomaHashService::class);
        $ijazah = Ijazah::create([
            'nama' => 'ASLI',
            'nim' => '1',
            'tempat_lahir' => 'JAKARTA',
            'tanggal_lahir' => '2001-01-01',
            'nama_institusi' => 'UNIVERSITAS',
            'fakultas' => 'TEKNIK',
            'prodi' => 'SISTEM INFORMASI',
            'gelar' => 'S.Kom',
            'nomor_ijazah' => 'IC-HASH-1',
            'tanggal_lulus' => '2026-01-01',
            'ipk' => 3.50,
            'version' => 1,
            'hash' => '',
            'created_by' => $akademik->id,
        ]);
        $ijazah->update(['hash' => $hashService->hash($ijazah)]);
        $ijazah->forceFill(['nama' => 'PALSU'])->save();

        $this->assertNotSame($ijazah->hash, $hashService->hash($ijazah->fresh()));
    }

    private function user(string $role): User
    {
        return User::create([
            'nama' => ucfirst($role),
            'email' => $role.'@example.test',
            'password' => Hash::make('password'),
            'role' => $role,
            'wallet_address' => '0x'.str_repeat((string) (array_search($role, ['akademik', 'rektor', 'admin'], true) + 1), 40),
        ]);
    }

    private function signature(): array
    {
        return [
            'wallet_address' => '0x'.str_repeat('1', 40),
            'signature' => '0x'.str_repeat('a', 130),
        ];
    }
}
