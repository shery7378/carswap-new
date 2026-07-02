<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'vehicle_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function replies()
    {
        return $this->hasMany(ContactReply::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ContactStatusHistory::class);
    }
}
