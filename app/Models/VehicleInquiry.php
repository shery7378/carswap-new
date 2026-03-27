<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInquiry extends Model
{
    protected $fillable = [
        'vehicle_id',
        'name',
        'email',
        'phone',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
