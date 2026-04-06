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
            // New granular HD image logic: "12 instead of 6 for 2 ads"
            $table->integer('hd_images_count')->default(6)->after('hd_images');
            $table->integer('hd_images_normal_count')->default(6)->after('hd_images_count');
            $table->integer('hd_images_ad_count')->default(1)->after('hd_images_normal_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['hd_images_count', 'hd_images_normal_count', 'hd_images_ad_count']);
        });
    }
};
