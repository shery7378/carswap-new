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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->json('exchange_preferences')->nullable()->after('description');
        });

        // Update sales methods to match user request
        // We'll update existing ones or insert new ones
        \DB::table('sales_methods')->updateOrInsert(['name' => 'Direct Sale'], ['name' => 'For Sale Only']);
        \DB::table('sales_methods')->updateOrInsert(['name' => 'Interchangeable'], ['name' => 'Exchange Only']);
        \DB::table('sales_methods')->updateOrInsert(['name' => 'For Sale / Exchange'], ['name' => 'For Sale / Exchange']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('exchange_preferences');
        });
    }
};
