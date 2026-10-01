<?php

namespace Database\Seeders;

use App\Models\Ijazah;
use App\Models\User;
use App\Services\DiplomaHashService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['nama' => 'Akademik IjazahChain', 'email' => 'akademik@ijazahchain.test', 'role' => 'akademik', 'wallet_address' => '0x1111111111111111111111111111111111111111'],
            ['nama' => 'Rektor IjazahChain', 'email' => 'rektor@ijazahchain.test', 'role' => 'rektor', 'wallet_address' => '0x2222222222222222222222222222222222222222'],
            ['nama' => 'Admin IjazahChain', 'email' => 'admin@ijazahchain.test', 'role' => 'admin', 'wallet_address' => '0x3333333333333333333333333333333333333333'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => Hash::make('password')]
            );
        }

        // Dummy Ijazah dihapus agar tabel ijazahs kosong.
    }
}
