<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'active' to 'published'
        DB::table('vehicles')->where('ad_status', 'active')->update(['ad_status' => 'published']);
        // Update existing 'inactive' or 'garage' (if any) to 'draft' or 'rejected'
        // For simplicity, we'll map inactive to rejected
        DB::table('vehicles')->where('ad_status', 'inactive')->update(['ad_status' => 'rejected']);
        
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('ad_status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('ad_status')->default('active')->change();
        });

        DB::table('vehicles')->where('ad_status', 'published')->update(['ad_status' => 'active']);
        DB::table('vehicles')->where('ad_status', 'rejected')->update(['ad_status' => 'inactive']);
    }
};
