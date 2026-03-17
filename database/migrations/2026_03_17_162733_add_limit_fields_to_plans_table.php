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
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('yearly_price', 10, 2)->nullable()->after('price');
            $table->integer('active_ads_limit')->default(0)->after('yearly_price');
            $table->integer('garage_ads_limit')->default(0)->after('active_ads_limit');
            $table->integer('expandable_slots')->default(0)->after('garage_ads_limit');
            $table->integer('highlight_ads')->default(0)->after('expandable_slots');
            $table->integer('hd_images')->default(0)->after('highlight_ads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
