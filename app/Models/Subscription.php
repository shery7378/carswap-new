<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'status',
        'starts_at',
        'ends_at',
        'next_billing_at',
        'duration',
        'stripe_session_id',
        'stripe_subscription_id',
        'stripe_customer_id',

        // Billing Information
        'billing_full_name',
        'billing_company_name',
        'billing_my_name',
        'billing_city',
        'billing_address',

        // Toggles / Selections
        'accepted_terms',
        'accepted_privacy',
        'accepted_recurring',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'next_billing_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
