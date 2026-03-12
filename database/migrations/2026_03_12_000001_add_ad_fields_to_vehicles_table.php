<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Adds ad-specific fields required by the "Upload an Ad" frontend form.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Ad publication control
            $table->string('ad_status')->default('active')->after('is_featured');
            // 'active'   => visible in public listing
            // 'garage'   => saved to owner's garage only (not publicly listed)
            // 'draft'    => saved draft, not published
            // 'inactive' => manually deactivated

            // Vehicle history report URL / text
            $table->string('history_report')->nullable()->after('vin_number');

            // Owner type: 'private' | 'dealer'
            $table->string('owner_type')->nullable()->default('private')->after('ad_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['ad_status', 'history_report', 'owner_type']);
        });
    }
};
