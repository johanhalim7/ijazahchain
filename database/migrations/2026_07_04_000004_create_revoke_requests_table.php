<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revoke_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ijazah_id')->constrained('ijazahs');
            $table->foreignId('requested_by')->constrained('users');
            $table->text('reason');
            $table->enum('status', ['Pending Rektor', 'Pending Akademik', 'Approved', 'Completed', 'Rejected'])->default('Pending Rektor')->index();
            $table->json('signatures')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('tx_hash')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revoke_requests');
    }
};
