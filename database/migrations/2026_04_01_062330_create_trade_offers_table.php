<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trade_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();

            // Offered Car Details
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('odometer')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('displacement')->nullable();
            $table->string('gearbox_type')->nullable();
            $table->string('drive_type')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('video_url')->nullable();
            $table->json('photos')->nullable();

            // Condition Info
            $table->string('exterior_condition')->nullable();
            $table->string('interior_condition')->nullable();
            $table->string('is_accident')->nullable(); // 'Again', 'Not', 'Don\'t know'

            // Contact Details
            $table->string('sender_first_name');
            $table->string('sender_last_name');
            $table->string('sender_email');
            $table->string('sender_phone');
            $table->text('comment')->nullable();

            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_offers');
    }
};
