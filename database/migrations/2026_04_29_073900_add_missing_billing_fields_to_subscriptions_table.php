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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('billing_type')->nullable()->after('duration'); // 'private' or 'company'
            $table->string('billing_postal_code')->nullable()->after('billing_city');
            $table->string('billing_tax_id')->nullable()->after('billing_company_name');
            $table->string('billing_email')->nullable()->after('billing_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'billing_type',
                'billing_postal_code',
                'billing_tax_id',
                'billing_email',
            ]);
        });
    }
};
