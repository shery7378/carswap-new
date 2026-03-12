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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_email_visible')) {
                $table->boolean('is_email_visible')->default(false);
            }
            if (!Schema::hasColumn('users', 'is_trader')) {
                $table->boolean('is_trader')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_email_visible')) {
                $table->dropColumn('is_email_visible');
            }
            if (Schema::hasColumn('users', 'is_trader')) {
                $table->dropColumn('is_trader');
            }
        });
    }
};
