<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeOffer extends Model
{
    protected $fillable = [
        'vehicle_id',
        'brand',
        'model',
        'year',
        'odometer',
        'fuel_type',
        'displacement',
        'gearbox_type',
        'drive_type',
        'exterior_color',
        'interior_color',
        'chassis_number',
        'owner_name',
        'video_url',
        'photos',
        'exterior_condition',
        'interior_condition',
        'is_accident',
        'sender_first_name',
        'sender_last_name',
        'sender_email',
        'sender_phone',
        'comment',
        'status',
    ];

    protected $casts = [
        'photos' => 'json',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
