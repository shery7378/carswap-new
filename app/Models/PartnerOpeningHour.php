<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOpeningHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'day',
        'open_time',
        'close_time',
        'is_closed',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
