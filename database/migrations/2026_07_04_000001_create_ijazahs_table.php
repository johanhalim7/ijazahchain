<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ijazahs', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nomor_ijazah')->unique();
            $table->json('payload_data'); // nama, nik, tempat_tanggal_lahir, nama_institusi, fakultas, prodi, gelar, tanggal_lulus, tanggal_diberikan
            $table->string('file_path')->nullable();
            $table->string('file_hash', 64)->nullable()->unique();
            $table->string('hash', 64)->index();
            $table->string('status', 50)->default('Draft')->index();
            $table->string('current_approver_role', 50)->nullable()->index();
            $table->unsignedInteger('workflow_id')->nullable();
            $table->json('approval_data')->nullable(); // catatan, approved_at/by, rejected_at/by, signatures
            $table->enum('blockchain_status', ['Not Uploaded', 'Uploaded', 'Revoked'])->default('Not Uploaded')->index();
            $table->json('blockchain_data')->nullable(); // tx_hash, block_number
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ijazahs');
    }
};
