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
        Schema::dropIfExists('vehicles');

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('body_type_id')->nullable();
            $table->unsignedBigInteger('fuel_type_id')->nullable();
            $table->unsignedBigInteger('transmission_id')->nullable();
            $table->unsignedBigInteger('drive_type_id')->nullable();
            $table->unsignedBigInteger('exterior_color_id')->nullable();
            $table->unsignedBigInteger('interior_color_id')->nullable();

            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('mileage')->nullable();
            $table->integer('year')->nullable();
            $table->integer('cylinder_capacity')->nullable(); // cm3
            $table->integer('performance')->nullable(); // kW
            $table->string('sales_method')->nullable(); // Interchangeable, etc.
            $table->string('status')->nullable(); // Novel, Failed, etc.
            $table->string('location')->nullable(); // Budapest, etc.
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->date('technical_expiration')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('video_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
