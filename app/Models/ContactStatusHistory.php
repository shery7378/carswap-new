<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactStatusHistory extends Model
{
    protected $fillable = [
        'contact_id',
        'old_status',
        'new_status',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
