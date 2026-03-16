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
        Schema::table('partner_reviews', function (Blueprint $table) {
            $table->boolean('is_genuine')->default(false)->after('reviewer_email');
            $table->integer('rating')->nullable()->change(); // Make rating nullable if not provided in the form
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_reviews', function (Blueprint $table) {
            $table->dropColumn('is_genuine');
            $table->integer('rating')->nullable(false)->change();
        });
    }
};
