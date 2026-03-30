<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tables = [
        'brands',
        'vehicle_models',
        'fuel_types',
        'transmissions',
        'drive_types',
        'body_types',
        'colors',
        'sales_methods',
        'document_types',
        'vehicle_statuses',
        'properties',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'is_active')) {
                        $table->boolean('is_active')->default(true);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'is_active')) {
                        $table->dropColumn('is_active');
                    }
                });
            }
        }
    }
};
