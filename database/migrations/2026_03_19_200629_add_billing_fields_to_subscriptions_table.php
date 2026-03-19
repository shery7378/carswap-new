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
            $table->string('billing_full_name')->nullable()->after('duration');
            $table->string('billing_company_name')->nullable()->after('billing_full_name');
            $table->string('billing_my_name')->nullable()->after('billing_company_name');
            $table->string('billing_city')->nullable()->after('billing_my_name');
            $table->string('billing_address')->nullable()->after('billing_city');

            // Compliance Checkboxes
            $table->boolean('accepted_terms')->default(false)->after('billing_address');
            $table->boolean('accepted_privacy')->default(false)->after('accepted_terms');
            $table->boolean('accepted_recurring')->default(false)->after('accepted_privacy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'billing_full_name',
                'billing_company_name',
                'billing_my_name',
                'billing_city',
                'billing_address',
                'accepted_terms',
                'accepted_privacy',
                'accepted_recurring'
            ]);
        });
    }
};
