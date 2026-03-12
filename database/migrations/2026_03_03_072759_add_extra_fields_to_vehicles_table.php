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
        Schema::table('vehicles', function (Blueprint $blueprint) {
            // Price Section Fields
            $blueprint->string('regular_price_label')->nullable()->after('price');
            $blueprint->text('regular_price_description')->nullable()->after('regular_price_label');
            $blueprint->decimal('sale_price', 15, 2)->nullable()->after('regular_price_description');
            $blueprint->string('sale_price_label')->nullable()->after('sale_price');
            $blueprint->string('instant_savings_label')->nullable()->after('sale_price_label');
            $blueprint->boolean('request_price_option')->default(false)->after('instant_savings_label');
            $blueprint->string('currency')->nullable()->after('request_price_option');

            // Location Section Fields
            $blueprint->string('address')->nullable()->after('location');
            $blueprint->string('latitude')->nullable()->after('address');
            $blueprint->string('longitude')->nullable()->after('latitude');

            // Additional Technical Info
            $blueprint->string('vin_number')->nullable()->after('year');
            $blueprint->string('engine_number')->nullable()->after('vin_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'regular_price_label',
                'regular_price_description',
                'sale_price',
                'sale_price_label',
                'instant_savings_label',
                'request_price_option',
                'currency',
                'address',
                'latitude',
                'longitude',
                'vin_number',
                'engine_number'
            ]);
        });
    }
};
