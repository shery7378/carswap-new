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
        Schema::create('partner_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->string('title')->nullable();
            $table->text('body');
            $table->string('reviewer_name');
            $table->string('reviewer_email');
            $table->boolean('is_approved')->default(true); // Auto-approve for now as it's a simple request
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_reviews');
    }
};
