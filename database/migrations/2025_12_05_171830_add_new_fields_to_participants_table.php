<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('full_name');
            $table->integer('voter_member_count')->default(0)->after('voter_id');
            $table->string('epic_voter_id_no')->nullable()->after('voter_member_count');
            $table->string('adharcard_no')->nullable()->unique()->after('epic_voter_id_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['age', 'voter_member_count', 'epic_voter_id_no', 'adharcard_no']);
        });
    }
};
