<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $fillable = [
        'contact_id',
        'subject',
        'message',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
