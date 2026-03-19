<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Stripe price IDs to plans so we know which Stripe Price to charge
        Schema::table('plans', function (Blueprint $table) {
            $table->string('stripe_price_id_monthly')->nullable()->after('slug');
            $table->string('stripe_price_id_yearly')->nullable()->after('stripe_price_id_monthly');
        });

        // Add Stripe metadata to subscriptions
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('plan_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_session_id');
            $table->string('stripe_customer_id')->nullable()->after('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['stripe_price_id_monthly', 'stripe_price_id_yearly']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['stripe_session_id', 'stripe_subscription_id', 'stripe_customer_id']);
        });
    }
};
