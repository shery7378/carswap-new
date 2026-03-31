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
        Schema::table('vehicle_inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_inquiries', 'preferred_date')) {
                $table->date('preferred_date')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('vehicle_inquiries', 'preferred_time')) {
                $table->string('preferred_time')->nullable()->after('preferred_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_inquiries', 'preferred_date')) {
                $table->dropColumn('preferred_date');
            }
            if (Schema::hasColumn('vehicle_inquiries', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }
        });
    }
};
