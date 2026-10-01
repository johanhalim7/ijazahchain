<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('verification_type');
            $table->text('input')->nullable();
            $table->string('hash', 64)->nullable();
            $table->string('blockchain_hash', 64)->nullable();
            $table->string('tx_hash')->nullable();
            $table->enum('result', ['VALID', 'INVALID', 'REVOKED'])->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_logs');
    }
};
