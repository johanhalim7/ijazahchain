<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('revoke_requests', function (Blueprint $table) {
            $table->string('status', 100)->change();
            $table->string('current_approver_role', 50)->nullable()->after('status');
            $table->foreignId('workflow_id')->nullable()->constrained('workflows')->after('ijazah_id');
        });
    }

    public function down(): void
    {
        Schema::table('revoke_requests', function (Blueprint $table) {
            $table->dropColumn('current_approver_role');
            $table->dropForeign(['workflow_id']);
            $table->dropColumn('workflow_id');
            // Reverting enum change is complex and usually not needed in down
        });
    }
};
