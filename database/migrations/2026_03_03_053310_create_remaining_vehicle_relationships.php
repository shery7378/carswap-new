<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Create auxiliary tables
        Schema::create('sales_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('vehicle_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Many-to-Many Pivot for Properties
        Schema::create('property_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Update vehicles table to use IDs for the ones we had as strings
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_method_id')->nullable()->after('drive_type_id');
            $table->unsignedBigInteger('document_type_id')->nullable()->after('sales_method_id');
            $table->unsignedBigInteger('vehicle_status_id')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['sales_method_id', 'document_type_id', 'vehicle_status_id']);
        });
        Schema::dropIfExists('property_vehicle');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('vehicle_statuses');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('sales_methods');
    }
};
