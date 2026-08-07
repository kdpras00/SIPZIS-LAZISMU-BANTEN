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
        Schema::table('muzakki', function (Blueprint $table) {
            // Add column for profile photo path
            $table->string('profile_photo', 500)->nullable()->after('campaign_url');
            
            // Add column for KTP photo path
            $table->string('ktp_photo', 500)->nullable()->after('profile_photo');
            
            // Add column for biodata (text content)
            $table->text('bio')->nullable()->after('ktp_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muzakki', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'ktp_photo', 'bio']);
        });
    }
};